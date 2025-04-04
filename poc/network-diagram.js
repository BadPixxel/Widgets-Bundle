import { DEFAULT_CONFIG } from './config.js';
import { PathUtils } from './path-utils.js';
import { ControlsManager } from './controls-manager.js';

class NetworkDiagram {
    constructor(config) {
        this.config = {
            ...DEFAULT_CONFIG,
            ...config,
            data: config.data || { nodes: [], links: [] },
            arrowStyle: 'arrow',  // Style par défaut
            lineStyle: 'solid',   // Style de ligne par défaut
            dashSpeed: 1.0        // Vitesse de défilement des pointillés
        };

        this.init();
        this.controls = new ControlsManager(this);
        this.setupToggleControls();
    }

    init() {
        this.createSVG();
        this.setupSimulation();
        this.createVisualElements();
        this.setupTooltips();
        this.startAnimation();
    }

    createSVG() {
        this.svg = d3.select(this.config.container)
            .attr("width", this.config.width)
            .attr("height", this.config.height);
    }

    setupSimulation() {
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
    }

    createVisualElements() {
        this.createLinks();
        this.createFlowArrows();
        this.createNodes();
        this.updateLinkStyle(this.config.linkType);
    }

    createLinks() {
        this.link = this.svg.append("g")
            .selectAll("path")
            .data(this.config.data.links)
            .join("path")
            .attr("class", "link")
            .attr("stroke-width", d => Math.sqrt(d.value))
            .attr("stroke-dasharray", this.config.lineStyle === 'dashed' ? "5,5" : "none")
            .attr("stroke-dashoffset", 0);
    }

    createFlowArrows() {
        this.flowArrows = this.svg.append("g")
            .selectAll("g")
            .data(this.config.data.links.flatMap(link => [
                { ...link, direction: 1 },
                { ...link, direction: -1 }
            ]))
            .join("g")
            .attr("class", d => `flow-arrow ${d.direction === -1 ? 'reverse' : ''}`);

        // Créer soit une flèche, soit un point, soit des chevrons selon le style
        this.flowArrows.each((d, i, nodes) => {
            const g = d3.select(nodes[i]);
            if (this.config.arrowStyle === 'arrow') {
                g.append("path")
                    .attr("d", "M-4,-2 L4,0 L-4,2 Z")
                    .attr("transform", `scale(${this.config.animation.arrowSize})`)
                    .attr("fill", d => d.direction === -1 ? "#4444ff" : "#ff4444");
            } else if (this.config.arrowStyle === 'chevrons') {
                // Créer un groupe pour les trois chevrons
                const chevronGroup = g.append("g")
                    .attr("class", "chevron-group");
                
                // Créer les trois chevrons dans le groupe
                for (let j = 0; j < 3; j++) {
                    chevronGroup.append("path")
                        .attr("d", "M-3,-2 L0,0 L-3,2 Z")
                        .attr("transform", `scale(${this.config.animation.arrowSize}) translate(${j * 8}, 0)`)
                        .attr("fill", d => d.direction === -1 ? "#4444ff" : "#ff4444");
                }
            } else {
                g.append("circle")
                    .attr("r", 3)
                    .attr("fill", d => d.direction === -1 ? "#4444ff" : "#ff4444");
            }
        });
    }

    createNodes() {
        this.node = this.svg.append("g")
            .selectAll("g")
            .data(this.config.data.nodes)
            .join("g")
            .attr("class", "node")
            .call(this.drag(this.simulation));

        this.node.append("circle")
            .attr("r", this.config.nodeRadius)
            .attr("fill", "white")
            .attr("stroke", d => this.config.colors[d.type] || "#2c3e50")
            .attr("stroke-width", 2);

        this.node.append("g")
            .attr("class", "node-icon")
            .attr("transform", `scale(0.8)`)
            .html(d => this.getNodeIcon(d));

        this.node.append("text")
            .attr("class", "node-label")
            .attr("dy", -this.config.nodeRadius - 5)
            .text(d => d.label);
    }

    getNodeIcon(d) {
        const color = this.config.colors[d.type] || "#2c3e50";
        const bars = {
            primary: 1, database: 2, web: 3, cache: 4,
            loadbalancer: 5, backup: 6, proxy: 7, storage: 8
        }[d.type] || 1;

        let paths = [
            `<path d="M-10,-10 L10,-10 L10,10 L-10,10 Z" fill="none" stroke="${color}" stroke-width="1"/>`,
            `<path d="M-5,-5 L5,-5 L5,5 L-5,5 Z" fill="none" stroke="${color}" stroke-width="1"/>`
        ];

        for (let i = 0; i < bars; i++) {
            const y = -2 + (i * 4);
            paths.push(`<path d="M-2,${y} L2,${y} L2,${y+2} L-2,${y+2} Z" fill="${color}"/>`);
        }

        return paths.join("\n");
    }

    drag(simulation) {
        return d3.drag()
            .on("start", (event, d) => {
                if (!event.active) simulation.alphaTarget(0.3).restart();
                d.fx = d.x;
                d.fy = d.y;
            })
            .on("drag", (event, d) => {
                d.fx = event.x;
                d.fy = event.y;
            })
            .on("end", (event, d) => {
                if (!event.active) simulation.alphaTarget(0);
                d.fx = null;
                d.fy = null;
            });
    }

    setupTooltips() {
        this.tooltip = d3.select(".tooltip");

        this.node
            .on("mouseover", (event, d) => {
                this.tooltip.style("opacity", 1)
                    .html(`<strong>${d.label}</strong><br>Type: ${d.type}`)
                    .style("left", (event.pageX + 10) + "px")
                    .style("top", (event.pageY - 10) + "px");
            })
            .on("mousemove", (event) => {
                this.tooltip.style("left", (event.pageX + 10) + "px")
                    .style("top", (event.pageY - 10) + "px");
            })
            .on("mouseout", () => {
                this.tooltip.style("opacity", 0);
            });
    }

    startAnimation() {
        this.simulation.on("tick", () => {
            this.updateLinkStyle(this.config.linkType);
            this.node.attr("transform", d => `translate(${d.x},${d.y})`);
        });

        this.startArrowAnimation();
        if (this.config.lineStyle === 'dashed') {
            this.startDashAnimation();
        }
    }

    startArrowAnimation() {
        const animate = () => {
            this.updateFlowArrows();
            requestAnimationFrame(animate);
        };
        requestAnimationFrame(animate);
    }

    startDashAnimation() {
        const animate = () => {
            if (this.config.lineStyle === 'dashed') {
                this.updateDashOffset();
                requestAnimationFrame(animate);
            }
        };
        requestAnimationFrame(animate);
    }

    updateFlowArrows() {
        this.flowArrows.each((d, i, nodes) => {
            const path = PathUtils.createPath(d, this.config.linkType);
            const progress = (performance.now() % this.config.animation.duration) / this.config.animation.duration;
            const { x, y, rotationAngle } = PathUtils.calculateArrowPosition(path, progress, d.direction);
            
            const g = d3.select(nodes[i]);
            if (this.config.arrowStyle === 'arrow') {
                g.attr("transform", `translate(${x},${y}) rotate(${rotationAngle})`);
            } else if (this.config.arrowStyle === 'chevrons') {
                // Déplacer le groupe entier des chevrons
                g.select(".chevron-group")
                    .attr("transform", `translate(${x},${y}) rotate(${rotationAngle})`);
            } else {
                g.attr("transform", `translate(${x},${y})`);
            }
        });
    }

    updateLinkStyle(type) {
        this.link.attr("d", d => PathUtils.createPath(d, type));
    }

    updateArrowStyle(style) {
        this.config.arrowStyle = style;
        this.updateVisualization();
    }

    updateArrowColors(color, direction) {
        const selector = direction === 'positive' ? '.flow-arrow:not(.reverse)' : '.flow-arrow.reverse';
        this.svg.selectAll(selector + ' path, ' + selector + ' circle').attr('fill', color);
    }

    updateDashOffset() {
        const offset = (performance.now() * this.config.dashSpeed) % 10;
        this.link.each((d, i, nodes) => {
            const link = d3.select(nodes[i]);
            if (d.bidirectional) {
                // Pour les liens bidirectionnels, on crée deux décalages différents
                // Un positif et un négatif pour créer l'effet de propagation dans les deux sens
                link.attr("stroke-dashoffset", -offset);
                // On crée un second pointillé qui se déplace dans l'autre sens
                if (!link.select(".dash-reverse").node()) {
                    link.append("path")
                        .attr("class", "dash-reverse")
                        .attr("d", link.attr("d"))
                        .attr("stroke", link.attr("stroke"))
                        .attr("stroke-width", link.attr("stroke-width"))
                        .attr("stroke-dasharray", "5,5")
                        .attr("stroke-dashoffset", offset);
                } else {
                    link.select(".dash-reverse")
                        .attr("stroke-dashoffset", offset);
                }
            } else {
                link.attr("stroke-dashoffset", -offset);
            }
        });
    }

    updateLineStyle(style) {
        this.config.lineStyle = style;
        this.link.attr("stroke-dasharray", style === 'dashed' ? "5,5" : "none");
        
        // Supprimer les pointillés inverses existants
        this.link.selectAll(".dash-reverse").remove();
        
        // Masquer ou afficher les flèches selon le style
        this.flowArrows.style("opacity", style === 'dashed' ? 0 : 1);
        
        if (style === 'dashed') {
            this.startDashAnimation();
        }
    }

    updateDashSpeed(speed) {
        this.config.dashSpeed = speed;
    }

    addNode(nodeData) {
        this.config.data.nodes.push(nodeData);
        this.simulation.nodes(this.config.data.nodes);
        this.updateVisualization();
    }

    removeNode(nodeId) {
        this.config.data.nodes = this.config.data.nodes.filter(n => n.id !== nodeId);
        this.config.data.links = this.config.data.links.filter(l => 
            l.source.id !== nodeId && l.target.id !== nodeId);
        this.simulation.nodes(this.config.data.nodes);
        this.simulation.force("link").links(this.config.data.links);
        this.updateVisualization();
    }

    createLink(sourceId, targetId) {
        const linkExists = this.config.data.links.some(link => 
            (link.source.id === sourceId && link.target.id === targetId) ||
            (link.source.id === targetId && link.target.id === sourceId)
        );

        if (!linkExists) {
            this.config.data.links.push({
                source: sourceId,
                target: targetId,
                value: 3,
                bidirectional: true
            });
            this.simulation.force("link").links(this.config.data.links);
            this.updateVisualization();
        }
    }

    updateVisualization() {
        this.svg.selectAll("*").remove();
        this.createVisualElements();
        this.setupTooltips();
        this.controls.updateNodeSelects();
        this.simulation.alpha(1).restart();
    }

    updateData(newData) {
        this.config.data = newData;
        this.updateVisualization();
    }

    resize(width, height) {
        this.config.width = width;
        this.config.height = height;
        this.svg
            .attr("width", width)
            .attr("height", height);
        this.simulation
            .force("center", d3.forceCenter(width / 2, height / 2))
            .force("x", d3.forceX(width / 2).strength(this.config.forces.centerStrength))
            .force("y", d3.forceY(height / 2).strength(this.config.forces.centerStrength));
    }

    setupToggleControls() {
        const toggleBtn = document.getElementById('toggle-controls');
        const controls = document.querySelector('.controls');
        
        toggleBtn.addEventListener('click', () => {
            controls.classList.toggle('collapsed');
        });
    }
}

export default NetworkDiagram; 
