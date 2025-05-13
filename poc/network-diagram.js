import { DEFAULT_CONFIG } from './config.js';
import { NodeManager } from './Utils/NodeManager.js';
import { LinkManager } from './Utils/LinkManager.js';
import { SimulationManager } from './Utils/SimulationManager.js';
import { TooltipManager } from './Utils/TooltipManager.js';
import { AnimationManager } from './Utils/AnimationManager.js';
import { ControlsManager } from './controls-manager.js';

export class NetworkDiagram {
    constructor(config) {
        this.config = {
            ...DEFAULT_CONFIG,
            ...config,
            data: config.data || { nodes: [], links: [] },
            arrowStyle: 'arrow',  // Style par défaut
            lineStyle: 'solid',   // Style de ligne par défaut
            dashSpeed: 0.5        // Vitesse de défilement des pointillés
        };

        this.init();
        this.controls = new ControlsManager(this);
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
        
        this.nodeManager = new NodeManager(this.config, this.svg, this.simulation);
        this.linkManager = new LinkManager(this.config, this.svg, this.simulation);
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
    }

    removeNode(nodeId) {
        const nodes = this.nodeManager.removeNode(nodeId);
        nodes.call(this.simulationManager.getDragBehavior());
        this.tooltipManager.setupTooltips(nodes);
        this.simulationManager.updateSimulation();
    }

    createLink(sourceId, targetId) {
        this.linkManager.createLink(sourceId, targetId);
        this.simulationManager.updateSimulation();
    }

    updateData(newData) {
        this.config.data = newData;
        this.createVisualElements();
        this.simulationManager.updateSimulation();
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
        const link = this.config.data.links.find(l => l.id === relationId);
        if (link) {
            link.bidirectional = isBidirectional;
            link.direction = unidirectionalDirection === 'forward' ? 1 : -1;
            this.linkManager.createLinks();
            this.linkManager.createFlowArrows();
        }
    }

    updateArrowColors(color, direction) {
        this.linkManager.updateArrowColors(color, direction);
    }

    updateLinkStyle(style) {
        this.linkManager.updateLinkStyle(style);
    }
} 
