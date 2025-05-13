export class SimulationManager {
    constructor(config) {
        this.config = config;
        this.simulation = null;
        this.updateQueue = [];
        this.isUpdating = false;
    }

    setupSimulation() {
        // S'assurer que les nœuds ont des positions initiales
        this.config.data.nodes.forEach(node => {
            if (!node.x) {
                node.x = Math.random() * this.config.width;
                node.y = Math.random() * this.config.height;
            }
        });

        // S'assurer que les liens ont des références correctes aux nœuds
        this.synchronizeData();

        this.simulation = d3.forceSimulation(this.config.data.nodes)
            .force("link", d3.forceLink(this.config.data.links)
                .id(d => d.id)
                .distance(this.config.linkDistance)
                .strength(this.config.forces.linkStrength))
            .force("charge", d3.forceManyBody().strength(this.config.forces.chargeStrength))
            .force("center", d3.forceCenter(this.config.width / 2, this.config.height / 2))
            .force("collision", d3.forceCollide().radius(this.config.forces.collisionRadius))
            .force("x", d3.forceX(this.config.width / 2).strength(this.config.forces.centerStrength))
            .force("y", d3.forceY(this.config.height / 2).strength(this.config.forces.centerStrength));

        // Ajouter un gestionnaire d'événements pour la fin de la simulation
        this.simulation.on("end", () => {
            this.isUpdating = false;
            this.processUpdateQueue();
        });

        return this.simulation;
    }

    synchronizeData() {
        // Synchroniser les références des liens
        this.config.data.links.forEach(link => {
            if (typeof link.source === 'string') {
                link.source = this.config.data.nodes.find(n => n.id === link.source);
            }
            if (typeof link.target === 'string') {
                link.target = this.config.data.nodes.find(n => n.id === link.target);
            }
        });

        // Filtrer les liens invalides
        this.config.data.links = this.config.data.links.filter(link => 
            link.source && link.target && 
            this.config.data.nodes.includes(link.source) && 
            this.config.data.nodes.includes(link.target)
        );
    }

    getDragBehavior() {
        return d3.drag()
            .on("start", (event, d) => {
                if (!event.active) this.simulation.alphaTarget(0.3).restart();
                d.fx = d.x;
                d.fy = d.y;
            })
            .on("drag", (event, d) => {
                d.fx = event.x;
                d.fy = event.y;
            })
            .on("end", (event, d) => {
                if (!event.active) this.simulation.alphaTarget(0);
                d.fx = null;
                d.fy = null;
            });
    }

    updateSimulation() {
        if (this.isUpdating) {
            this.updateQueue.push(() => this.performUpdate());
            return;
        }

        this.performUpdate();
    }

    performUpdate() {
        this.isUpdating = true;
        
        // Synchroniser les données
        this.synchronizeData();

        // Mettre à jour les forces
        if (this.simulation) {
            this.simulation.force("link").links(this.config.data.links);
            this.simulation.force("center")
                .x(this.config.width / 2)
                .y(this.config.height / 2);
            
            // Redémarrer la simulation avec une alpha plus élevée
            this.simulation.alpha(0.3).restart();
        }
    }

    processUpdateQueue() {
        if (this.updateQueue.length > 0) {
            const nextUpdate = this.updateQueue.shift();
            nextUpdate();
        }
    }

    resize(width, height) {
        this.config.width = width;
        this.config.height = height;
        this.updateSimulation();
    }
} 
