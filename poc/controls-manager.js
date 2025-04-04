export class ControlsManager {
    constructor(networkDiagram) {
        this.diagram = networkDiagram;
        this.setupControls();
    }

    setupControls() {
        this.setupLinkTypeControl();
        this.setupLineStyleControl();
        this.setupArrowStyleControl();
        this.setupColorControls();
        this.setupServerControls();
        this.setupRelationshipControls();
    }

    setupLinkTypeControl() {
        const linkTypeSelect = document.getElementById('link-type');
        linkTypeSelect.value = this.diagram.config.linkType;
        
        linkTypeSelect.addEventListener('change', (e) => {
            this.diagram.config.linkType = e.target.value;
            this.diagram.updateLinkStyle(this.diagram.config.linkType);
        });
    }

    setupLineStyleControl() {
        const lineStyleSelect = document.getElementById('line-style');
        const dashSpeedControl = document.querySelector('.dash-speed-control');
        const dashSpeedInput = document.getElementById('dash-speed');
        const dashSpeedValue = document.getElementById('dash-speed-value');

        lineStyleSelect.value = this.diagram.config.lineStyle;
        dashSpeedInput.value = this.diagram.config.dashSpeed;
        dashSpeedValue.textContent = `${this.diagram.config.dashSpeed.toFixed(1)}x`;

        // Afficher/masquer le contrôle de vitesse selon le style
        dashSpeedControl.style.display = this.diagram.config.lineStyle === 'dashed' ? 'flex' : 'none';

        lineStyleSelect.addEventListener('change', (e) => {
            this.diagram.updateLineStyle(e.target.value);
            dashSpeedControl.style.display = e.target.value === 'dashed' ? 'flex' : 'none';
        });

        dashSpeedInput.addEventListener('input', (e) => {
            const speed = parseFloat(e.target.value);
            dashSpeedValue.textContent = `${speed.toFixed(1)}x`;
            this.diagram.updateDashSpeed(speed);
        });
    }

    setupArrowStyleControl() {
        const arrowStyleSelect = document.getElementById('arrow-style');
        arrowStyleSelect.value = this.diagram.config.arrowStyle;
        
        arrowStyleSelect.addEventListener('change', (e) => {
            this.diagram.updateArrowStyle(e.target.value);
        });
    }

    setupColorControls() {
        const positiveColorInput = document.getElementById('positive-direction-color');
        const negativeColorInput = document.getElementById('negative-direction-color');
        const positiveColorBox = positiveColorInput.nextElementSibling;
        const negativeColorBox = negativeColorInput.nextElementSibling;

        positiveColorInput.addEventListener('input', (e) => {
            const color = e.target.value;
            positiveColorBox.style.backgroundColor = color;
            this.diagram.updateArrowColors(color, 'positive');
        });

        negativeColorInput.addEventListener('input', (e) => {
            const color = e.target.value;
            negativeColorBox.style.backgroundColor = color;
            this.diagram.updateArrowColors(color, 'negative');
        });
    }

    setupServerControls() {
        document.getElementById('add-server').addEventListener('click', () => {
            const id = document.getElementById('server-id').value;
            const label = document.getElementById('server-label').value;
            const type = document.getElementById('server-type').value;

            if (id && label) {
                this.diagram.addNode({ id, label, type });
                this.clearServerInputs();
                this.updateServerList();
            }
        });

        document.getElementById('remove-server').addEventListener('click', () => {
            const serverId = document.getElementById('server-to-remove').value;
            if (serverId) {
                this.diagram.removeNode(serverId);
                this.updateServerList();
            }
        });
    }

    setupRelationshipControls() {
        document.getElementById('create-relation').addEventListener('click', () => {
            const sourceId = document.getElementById('source-node').value;
            const targetId = document.getElementById('target-node').value;

            if (sourceId && targetId) {
                this.diagram.createLink(sourceId, targetId);
                this.clearRelationshipInputs();
            }
        });
    }

    updateServerList() {
        const select = document.getElementById('server-to-remove');
        select.innerHTML = '<option value="">Sélectionner un serveur</option>';
        this.diagram.config.data.nodes.forEach(node => {
            const option = document.createElement('option');
            option.value = node.id;
            option.textContent = `${node.label} (${node.id})`;
            select.appendChild(option);
        });
    }

    updateNodeSelects() {
        const sourceSelect = document.getElementById('source-node');
        const targetSelect = document.getElementById('target-node');
        
        const currentSource = sourceSelect.value;
        const currentTarget = targetSelect.value;
        
        const updateSelect = (select) => {
            select.innerHTML = '<option value="">Sélectionner un serveur</option>';
            this.diagram.config.data.nodes.forEach(node => {
                const option = document.createElement('option');
                option.value = node.id;
                option.textContent = `${node.label} (${node.id})`;
                select.appendChild(option);
            });
        };

        updateSelect(sourceSelect);
        updateSelect(targetSelect);

        if (this.diagram.config.data.nodes.some(n => n.id === currentSource)) {
            sourceSelect.value = currentSource;
        }
        if (this.diagram.config.data.nodes.some(n => n.id === currentTarget)) {
            targetSelect.value = currentTarget;
        }
    }

    clearServerInputs() {
        document.getElementById('server-id').value = '';
        document.getElementById('server-label').value = '';
    }

    clearRelationshipInputs() {
        document.getElementById('source-node').value = '';
        document.getElementById('target-node').value = '';
    }
} 
