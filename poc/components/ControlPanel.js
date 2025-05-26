import { StyleControls } from './StyleControls.js';
import { ServerControls } from './ServerControls.js';
import { RelationshipControls } from './RelationshipControls.js';

export class ControlPanel {
    constructor(container) {
        this.container = container;
        this.render();
        this.setupComponents();
        this.setupToggleControls();
    }

    render() {
        // Créer le conteneur du bouton
        const toggleContainer = document.createElement('div');
        toggleContainer.className = 'toggle-container';
        toggleContainer.innerHTML = `
            <button id="toggle-controls" class="toggle-controls-btn">◀</button>
        `;
        this.container.appendChild(toggleContainer);

        // Créer le panneau de contrôle
        const controlPanel = document.createElement('div');
        controlPanel.className = 'controls';
        controlPanel.innerHTML = `
            <div class="controls-header">
                <h3>Contrôles</h3>
            </div>
        `;
        this.container.appendChild(controlPanel);
        this.controlPanel = controlPanel;
    }

    setupComponents() {
        this.styleControls = new StyleControls(this.controlPanel);
        this.serverControls = new ServerControls(this.controlPanel);
        this.relationshipControls = new RelationshipControls(this.controlPanel);
    }

    setupToggleControls() {
        const toggleBtn = document.getElementById('toggle-controls');
        toggleBtn.addEventListener('click', () => {
            this.controlPanel.classList.toggle('collapsed');
            toggleBtn.classList.toggle('rotated');
        });
    }

    getElements() {
        return {
            style: this.styleControls.getElements(),
            server: this.serverControls.getElements(),
            relationship: this.relationshipControls.getElements()
        };
    }

    updateNodeSelects(nodes) {
        this.serverControls.updateServerList(nodes);
        this.relationshipControls.updateNodeSelects(nodes);
    }

    updateRelationSelects(links) {
        this.relationshipControls.updateRelationSelects(links);
    }
} 
