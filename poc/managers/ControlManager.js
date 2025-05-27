import { ControlPanel } from '../components/ControlPanel.js';

export class ControlManager {
    constructor(networkDiagram) {
        this.diagram = networkDiagram;
        this.controlPanel = new ControlPanel(document.getElementById('control-panel'));
        this.setupEventListeners();
        this.updateNodeSelects();
        this.updateRelationSelects();
    }

    setupEventListeners() {
        const elements = this.controlPanel.getElements();

        // Style controls
        elements.style.linkType.addEventListener('change', (e) => {
            this.diagram.config.linkType = e.target.value;
            this.diagram.updateLinkStyle(this.diagram.config.linkType);
        });

        elements.style.lineStyle.addEventListener('change', (e) => {
            this.diagram.updateLineStyle(e.target.value);
            elements.style.dashSpeed.parentElement.style.display = 
                e.target.value === 'dashed' ? 'flex' : 'none';
        });

        elements.style.dashSpeed.addEventListener('input', (e) => {
            const speed = parseFloat(e.target.value);
            elements.style.dashSpeedValue.textContent = `${speed.toFixed(1)}x`;
            this.diagram.updateDashSpeed(speed);
        });

        elements.style.arrowStyle.addEventListener('change', (e) => {
            this.diagram.updateArrowStyle(e.target.value);
        });

        // Ajout gestion couleur loading bar
        if (elements.style.loadingBarColor) {
            elements.style.loadingBarColor.addEventListener('input', (e) => {
                const color = e.target.value;
                elements.style.loadingBarColorBox.style.backgroundColor = color;
                this.diagram.config.animation.loadingBar.color = color;
                // Rafraîchir les loading bars
                if (this.diagram.config.arrowStyle === 'loading-bar') {
                    this.diagram.updateArrowStyle('loading-bar');
                }
            });
        }

        elements.style.positiveColor.addEventListener('input', (e) => {
            const color = e.target.value;
            e.target.nextElementSibling.style.backgroundColor = color;
            this.diagram.updateArrowColors(color, 'positive');
        });

        elements.style.negativeColor.addEventListener('input', (e) => {
            const color = e.target.value;
            e.target.nextElementSibling.style.backgroundColor = color;
            this.diagram.updateArrowColors(color, 'negative');
        });

        // Server controls
        elements.server.addServerBtn.addEventListener('click', () => {
            const id = elements.server.serverId.value;
            const label = elements.server.serverLabel.value;
            const type = elements.server.serverType.value;

            if (id && label) {
                this.diagram.addNode({ id, label, type });
                this.clearServerInputs();
            }
        });

        elements.server.removeServerBtn.addEventListener('click', () => {
            const serverId = elements.server.serverToRemove.value;
            if (serverId) {
                this.diagram.removeNode(serverId);
            }
        });

        // Relationship controls
        elements.relationship.createRelationBtn.addEventListener('click', () => {
            const sourceId = elements.relationship.sourceNode.value;
            const targetId = elements.relationship.targetNode.value;

            if (sourceId && targetId) {
                this.diagram.createLink(sourceId, targetId);
                this.clearRelationshipInputs();
            }
        });

        elements.relationship.transferDirection.addEventListener('change', () => {
            elements.relationship.unidirectionalDirection.style.display = 
                elements.relationship.transferDirection.value === 'unidirectional' ? 'block' : 'none';
        });

        elements.relationship.updateDirectionBtn.addEventListener('click', () => {
            const relationId = elements.relationship.relationToModify.value;
            const isBidirectional = elements.relationship.transferDirection.value === 'bidirectional';
            const unidirectionalDirection = elements.relationship.unidirectionalDirection.value;
            
            if (relationId) {
                this.diagram.updateTransferDirection(
                    relationId, 
                    isBidirectional,
                    unidirectionalDirection
                );
            }
        });
    }

    updateNodeSelects() {
        this.controlPanel.updateNodeSelects(this.diagram.config.data.nodes);
    }

    updateRelationSelects() {
        this.controlPanel.updateRelationSelects(this.diagram.config.data.links);
    }

    clearServerInputs() {
        const elements = this.controlPanel.getElements();
        elements.server.serverId.value = '';
        elements.server.serverLabel.value = '';
    }

    clearRelationshipInputs() {
        const elements = this.controlPanel.getElements();
        elements.relationship.sourceNode.value = '';
        elements.relationship.targetNode.value = '';
    }
} 
