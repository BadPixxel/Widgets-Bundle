export class ControlsManager {
    constructor(networkDiagram) {
        this.diagram = networkDiagram;
        this.setupControls();
        // Initialiser les sélecteurs avec les données existantes
        this.updateNodeSelects();
        this.updateRelationSelects();
        this.updateServerList();
    }

    setupControls() {
        this.setupLinkTypeControl();
        this.setupLineStyleControl();
        this.setupArrowStyleControl();
        this.setupColorControls();
        this.setupServerControls();
        this.setupRelationshipControls();
        this.setupTransferDirectionControls();
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

    setupTransferDirectionControls() {
        const relationSelect = document.getElementById('relation-to-modify');
        const directionSelect = document.getElementById('transfer-direction');
        const unidirectionalDirectionSelect = document.getElementById('unidirectional-direction');
        const updateButton = document.getElementById('update-direction');

        directionSelect.addEventListener('change', () => {
            unidirectionalDirectionSelect.style.display = 
                directionSelect.value === 'unidirectional' ? 'block' : 'none';
        });

        updateButton.addEventListener('click', () => {
            const relationId = relationSelect.value;
            const isBidirectional = directionSelect.value === 'bidirectional';
            const unidirectionalDirection = unidirectionalDirectionSelect.value;
            
            if (relationId) {
                this.diagram.updateTransferDirection(
                    relationId, 
                    isBidirectional,
                    unidirectionalDirection
                );
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

    updateRelationSelects() {
        const relationSelect = document.getElementById('relation-to-modify');
        const directionSelect = document.getElementById('transfer-direction');
        const unidirectionalDirectionSelect = document.getElementById('unidirectional-direction');
        const currentValue = relationSelect.value;
        
        relationSelect.innerHTML = '<option value="">Sélectionner une relation</option>';
        this.diagram.config.data.links.forEach(link => {
            const option = document.createElement('option');
            const relationId = `${link.source.id}-${link.target.id}`;
            option.value = relationId;
            const directionSymbol = link.bidirectional ? '↔' : (link.direction === 'forward' ? '→' : '←');
            option.textContent = `${link.source.label} ${directionSymbol} ${link.target.label}`;
            relationSelect.appendChild(option);
        });

        if (currentValue && this.diagram.config.data.links.some(l => 
            `${l.source.id}-${l.target.id}` === currentValue)) {
            relationSelect.value = currentValue;
            const link = this.diagram.config.data.links.find(l => 
                `${l.source.id}-${l.target.id}` === currentValue
            );
            if (link) {
                directionSelect.value = link.bidirectional ? 'bidirectional' : 'unidirectional';
                unidirectionalDirectionSelect.style.display = 
                    directionSelect.value === 'unidirectional' ? 'block' : 'none';
                if (!link.bidirectional) {
                    // On détermine la direction en fonction de l'ordre des nœuds
                    const isForward = `${link.source.id}-${link.target.id}` === currentValue;
                    unidirectionalDirectionSelect.value = isForward ? 'forward' : 'reverse';
                }
            }
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
