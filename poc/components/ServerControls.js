import { SERVER_TYPES } from '../config/types-config.js';

export class ServerControls {
    constructor(container) {
        this.container = container;
        this.render();
    }

    render() {
        const serverControls = document.createElement('div');
        serverControls.className = 'control-group';
        
        // Section Ajouter un serveur
        const addServerSection = document.createElement('div');
        addServerSection.innerHTML = `
            <h4>Ajouter un serveur</h4>
            <input type="text" id="server-id" placeholder="ID du serveur">
            <input type="text" id="server-label" placeholder="Label du serveur">
            <select id="server-type">
                ${SERVER_TYPES.map(type => 
                    `<option value="${type.value}">${type.label}</option>`
                ).join('')}
            </select>
            <button id="add-server">Ajouter</button>
        `;
        
        // Section Supprimer un serveur
        const removeServerSection = document.createElement('div');
        removeServerSection.innerHTML = `
            <h4>Supprimer un serveur</h4>
            <select id="server-to-remove">
                <option value="">Sélectionner un serveur</option>
            </select>
            <button id="remove-server">Supprimer</button>
        `;
        
        serverControls.appendChild(addServerSection);
        serverControls.appendChild(removeServerSection);
        this.container.appendChild(serverControls);
    }

    getElements() {
        return {
            serverId: document.getElementById('server-id'),
            serverLabel: document.getElementById('server-label'),
            serverType: document.getElementById('server-type'),
            addServerBtn: document.getElementById('add-server'),
            serverToRemove: document.getElementById('server-to-remove'),
            removeServerBtn: document.getElementById('remove-server')
        };
    }

    updateServerList(nodes) {
        const select = document.getElementById('server-to-remove');
        select.innerHTML = '<option value="">Sélectionner un serveur</option>';
        nodes.forEach(node => {
            const option = document.createElement('option');
            option.value = node.id;
            option.textContent = `${node.label} (${node.id})`;
            select.appendChild(option);
        });
    }
} 
