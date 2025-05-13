export class NodeManager {
  constructor(config, svg, simulation) {
    this.config = config;
    this.svg = svg;
    this.simulation = simulation;
    this.nodes = null;
    this.updateQueue = [];
    this.isUpdating = false;
  }

  createNodes() {
    if (this.isUpdating) {
      this.updateQueue.push(() => this.performCreateNodes());
      return this.nodes;
    }

    return this.performCreateNodes();
  }

  performCreateNodes() {
    this.isUpdating = true;

    // Initialiser les positions des nœuds s'ils n'en ont pas
    this.config.data.nodes.forEach((node) => {
      if (!node.x) {
        node.x = Math.random() * this.config.width;
        node.y = Math.random() * this.config.height;
      }
    });

    // Créer un nouveau groupe pour les nœuds
    const nodeGroup = this.svg.append("g")
      .attr("class", "node-group");

    this.nodes = nodeGroup
      .selectAll("g")
      .data(this.config.data.nodes)
      .join("g")
      .attr("class", "node");

    this.nodes
      .append("circle")
      .attr("r", this.config.nodeRadius)
      .attr("fill", "white")
      .attr("stroke", (d) => this.config.colors[d.type] || "#2c3e50")
      .attr("stroke-width", 2);

    this.nodes
      .append("g")
      .attr("class", "node-icon")
      .attr("transform", `scale(0.8)`)
      .html((d) => this.getNodeIcon(d));

    this.nodes
      .append("text")
      .attr("class", "node-label")
      .attr("dy", -this.config.nodeRadius - 5)
      .text((d) => d.label);

    this.isUpdating = false;
    this.processUpdateQueue();

    return this.nodes;
  }

  processUpdateQueue() {
    if (this.updateQueue.length > 0) {
      const nextUpdate = this.updateQueue.shift();
      nextUpdate();
    }
  }

  getNodeIcon(d) {
    const color = this.config.colors[d.type] || "#2c3e50";
    const bars =
      {
        primary: 1,
        database: 2,
        web: 3,
        cache: 4,
        loadbalancer: 5,
        backup: 6,
        proxy: 7,
        storage: 8,
      }[d.type] || 1;

    let paths = [
      `<path d="M-10,-10 L10,-10 L10,10 L-10,10 Z" fill="none" stroke="${color}" stroke-width="1"/>`,
      `<path d="M-5,-5 L5,-5 L5,5 L-5,5 Z" fill="none" stroke="${color}" stroke-width="1"/>`,
    ];

    for (let i = 0; i < bars; i++) {
      const y = -2 + i * 4;
      paths.push(
        `<path d="M-2,${y} L2,${y} L2,${y + 2} L-2,${
          y + 2
        } Z" fill="${color}"/>`
      );
    }

    return paths.join("\n");
  }

  updateNodes() {
    if (this.nodes) {
      this.nodes.attr("transform", (d) => `translate(${d.x},${d.y})`);
    }
  }

  addNode(nodeData) {
    // Vérifier si le nœud existe déjà
    const nodeExists = this.config.data.nodes.some(node => node.id === nodeData.id);
    if (nodeExists) {
      return this.nodes;
    }

    // Initialiser la position du nouveau nœud
    nodeData.x = Math.random() * this.config.width;
    nodeData.y = Math.random() * this.config.height;
    
    // Ajouter le nœud aux données
    this.config.data.nodes.push(nodeData);
    
    // Mettre à jour la simulation
    this.simulation.nodes(this.config.data.nodes);
    
    // Recréer les nœuds visuels
    return this.createNodes();
  }

  removeNode(nodeId) {
    // Vérifier si le nœud existe
    const nodeExists = this.config.data.nodes.some(node => node.id === nodeId);
    if (!nodeExists) {
      return this.nodes;
    }

    // Supprimer les liens associés au nœud
    this.config.data.links = this.config.data.links.filter(
      (link) => {
        const sourceId = typeof link.source === 'string' ? link.source : link.source.id;
        const targetId = typeof link.target === 'string' ? link.target : link.target.id;
        return sourceId !== nodeId && targetId !== nodeId;
      }
    );
    
    // Supprimer le nœud des données
    this.config.data.nodes = this.config.data.nodes.filter(
      (node) => node.id !== nodeId
    );

    // Nettoyer les éléments visuels existants
    if (this.nodes) {
      this.nodes.remove();
      this.nodes = null;
    }

    // Mettre à jour la simulation
    if (this.simulation) {
      this.simulation.nodes(this.config.data.nodes);
      this.simulation.force("link").links(this.config.data.links);
      this.simulation.alpha(0.3).restart();
    }
    
    // Recréer les nœuds visuels
    return this.createNodes();
  }
}
