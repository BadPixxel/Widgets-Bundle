export class RelationshipControls {
    constructor(container) {
        this.container = container;
        this.render();
    }

    render() {
        const relationshipControls = document.createElement('div');
        relationshipControls.className = 'control-group';
        
        // Section Créer une relation
        const createRelationSection = document.createElement('div');
        createRelationSection.innerHTML = `
            <h4>Créer une relation</h4>
            <div class="node-relationship-controls">
                <select id="source-node" class="node-select">
                    <option value="">Source</option>
                </select>
                <select id="target-node" class="node-select">
                    <option value="">Destination</option>
                </select>
                <button id="create-relation" class="create-relation-btn">Créer la relation</button>
            </div>
        `;
        
        // Section Modifier le sens de transfert
        const modifyDirectionSection = document.createElement('div');
        modifyDirectionSection.innerHTML = `
            <h4>Modifier le sens de transfert</h4>
            <select id="relation-to-modify">
                <option value="">Sélectionner une relation</option>
            </select>
            <select id="transfer-direction">
                <option value="bidirectional">Dans les deux sens</option>
                <option value="unidirectional">Unidirectionnel</option>
            </select>
            <select id="unidirectional-direction" style="display: none;">
                <option value="forward">Source → Destination</option>
                <option value="reverse">Destination → Source</option>
            </select>
            <button id="update-direction">Mettre à jour</button>
        `;
        
        relationshipControls.appendChild(createRelationSection);
        relationshipControls.appendChild(modifyDirectionSection);
        this.container.appendChild(relationshipControls);
    }

    getElements() {
        return {
            sourceNode: document.getElementById('source-node'),
            targetNode: document.getElementById('target-node'),
            createRelationBtn: document.getElementById('create-relation'),
            relationToModify: document.getElementById('relation-to-modify'),
            transferDirection: document.getElementById('transfer-direction'),
            unidirectionalDirection: document.getElementById('unidirectional-direction'),
            updateDirectionBtn: document.getElementById('update-direction')
        };
    }

    updateNodeSelects(nodes) {
        const updateSelect = (select) => {
            select.innerHTML = '<option value="">Sélectionner un serveur</option>';
            nodes.forEach(node => {
                const option = document.createElement('option');
                option.value = node.id;
                option.textContent = `${node.label} (${node.id})`;
                select.appendChild(option);
            });
        };

        updateSelect(document.getElementById('source-node'));
        updateSelect(document.getElementById('target-node'));
    }

    updateRelationSelects(links) {
        const relationSelect = document.getElementById('relation-to-modify');
        relationSelect.innerHTML = '<option value="">Sélectionner une relation</option>';
        
        links.forEach(link => {
            const option = document.createElement('option');
            const relationId = `${link.source.id}-${link.target.id}`;
            option.value = relationId;
            const directionSymbol = link.bidirectional ? '↔' : (link.direction === 1 ? '→' : '←');
            option.textContent = `${link.source.label} ${directionSymbol} ${link.target.label}`;
            relationSelect.appendChild(option);
        });
    }
} 
