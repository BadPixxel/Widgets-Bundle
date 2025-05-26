import { DEFAULT_CONFIG, COLOR_CONFIG, ANIMATION_CONFIG } from './config/index.js';
import { NodeManager } from './Utils/NodeManager.js';
import { LinkManager } from './Utils/LinkManager.js';
import { SimulationManager } from './Utils/SimulationManager.js';
import { TooltipManager } from './Utils/TooltipManager.js';
import { AnimationManager } from './Utils/AnimationManager.js';
import { ControlManager } from './controls-manager.js';

export class NetworkDiagram {
    constructor(config) {
        this.config = {
            ...DEFAULT_CONFIG,
            ...config,
            data: config.data || { nodes: [], links: [] },
            colors: COLOR_CONFIG,
            animation: ANIMATION_CONFIG,
            arrowStyle: 'arrow',  // Style par défaut
            lineStyle: 'solid',   // Style de ligne par défaut
            dashSpeed: 0.5        // Vitesse de défilement des pointillés
        };

        this.init();
        this.controls = new ControlManager(this);
        this.setupToggleControls();
    }

    init() {
        this.createSVG();
        this.setupManagers();
        this.createVisualElements();
        this.startAnimation();
    }

    createSVG() {
        this.svg = d3.select(this.config.container)
            .attr("width", this.config.width)
            .attr("height", this.config.height);
    }

    setupManagers() {
        this.simulationManager = new SimulationManager(this.config);
        this.simulation = this.simulationManager.setupSimulation();
        
        this.linkManager = new LinkManager(this.config, this.svg, this.simulation);
        this.config.linkManager = this.linkManager;  // Ajouter le linkManager à la configuration
        this.nodeManager = new NodeManager(this.config, this.svg, this.simulation);
        this.tooltipManager = new TooltipManager();
        this.animationManager = new AnimationManager(this.config, this.linkManager);
    }

    createVisualElements() {
        console.log('Creating visual elements...');
        
        // Nettoyer complètement le SVG
        this.svg.selectAll("*").remove();
        
        // Recréer le groupe principal
        this.svg = d3.select(this.config.container)
            .attr("width", this.config.width)
            .attr("height", this.config.height);
        
        // Créer les nouveaux éléments
        const nodes = this.nodeManager.createNodes();
        console.log('Nodes created:', nodes);
        
        // Créer les liens immédiatement
        this.linkManager.createLinks();
        this.linkManager.createFlowArrows();
        
        // Réappliquer le comportement de drag
        nodes.call(this.simulationManager.getDragBehavior());
        
        // Réappliquer les tooltips
        this.tooltipManager.setupTooltips(nodes);
    }

    startAnimation() {
        console.log('Starting animation...');
        this.simulation.on("tick", () => {
            this.nodeManager.updateNodes();
            if (this.linkManager.links) {
                this.linkManager.updateLinkPositions();
            }
        });

        this.animationManager.startArrowAnimation();
        if (this.config.lineStyle === 'dashed') {
            this.animationManager.startDashAnimation();
        }
    }

    updateArrowStyle(style) {
        this.config.arrowStyle = style;
        this.linkManager.updateArrowStyle(style);
    }

    updateLineStyle(style) {
        this.config.lineStyle = style;
        this.linkManager.updateLineStyle(style);
        if (style === 'dashed') {
            this.animationManager.startDashAnimation();
        } else {
            this.animationManager.stopAnimation();
        }
    }

    updateDashSpeed(speed) {
        this.config.dashSpeed = speed;
        this.animationManager.updateDashSpeed(speed);
    }

    addNode(nodeData) {
        const nodes = this.nodeManager.addNode(nodeData);
        nodes.call(this.simulationManager.getDragBehavior());
        this.tooltipManager.setupTooltips(nodes);
        this.simulationManager.updateSimulation();
        // Mettre à jour les listes déroulantes
        this.controls.updateNodeSelects();
        this.controls.updateRelationSelects();
    }

    removeNode(nodeId) {
        const nodes = this.nodeManager.removeNode(nodeId);
        nodes.call(this.simulationManager.getDragBehavior());
        this.tooltipManager.setupTooltips(nodes);
        this.simulationManager.updateSimulation();
        
        // Mettre à jour les listes déroulantes
        this.controls.updateNodeSelects();
        this.controls.updateRelationSelects();
    }

    createLink(sourceId, targetId) {
        this.linkManager.createLink(sourceId, targetId);
        this.simulationManager.updateSimulation();
        // Mettre à jour les listes déroulantes
        this.controls.updateNodeSelects();
        this.controls.updateRelationSelects();
    }

    updateData(newData) {
        this.config.data = newData;
        this.createVisualElements();
        this.simulationManager.updateSimulation();
        // Mettre à jour les listes déroulantes
        this.controls.updateNodeSelects();
        this.controls.updateRelationSelects();
    }

    resize(width, height) {
        this.config.width = width;
        this.config.height = height;
        this.svg.attr("width", width).attr("height", height);
        this.simulationManager.resize(width, height);
    }

    setupToggleControls() {
        const controls = document.querySelector('.controls');
        if (controls) {
            controls.style.display = 'block';
        }
    }

    updateTransferDirection(relationId, isBidirectional, unidirectionalDirection = 'forward') {
        // Extraire les IDs source et target de l'ID de relation
        const [sourceId, targetId] = relationId.split('-');
        
        // Trouver le lien correspondant
        const link = this.config.data.links.find(l => {
            const linkSourceId = typeof l.source === 'string' ? l.source : l.source.id;
            const linkTargetId = typeof l.target === 'string' ? l.target : l.target.id;
            return (linkSourceId === sourceId && linkTargetId === targetId) ||
                   (linkSourceId === targetId && linkTargetId === sourceId);
        });

        if (link) {
            link.bidirectional = isBidirectional;
            link.direction = unidirectionalDirection === 'forward' ? 1 : -1;
            
            // Mettre à jour la simulation
            this.simulationManager.synchronizeData();
            this.simulation.force("link").links(this.config.data.links);
            
            // Recréer les liens et les flèches
            this.linkManager.createLinks();
            this.linkManager.createFlowArrows();
            
            // Redémarrer la simulation
            this.simulation.alpha(0.3).restart();
            // Mettre à jour les listes déroulantes
            this.controls.updateNodeSelects();
            this.controls.updateRelationSelects();
        }
    }

    updateArrowColors(color, direction) {
        this.linkManager.updateArrowColors(color, direction);
    }

    updateLinkStyle(style) {
        this.linkManager.updateLinkStyle(style);
    }
} 
