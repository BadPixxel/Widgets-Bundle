export class LinkManager {
    constructor(config, svg, simulation) {
        this.config = {
            ...config,
            arrowColors: {
                positive: "#ff4444",
                negative: "#4444ff"
            }
        };
        this.svg = svg;
        this.simulation = simulation;
        this.links = null;
        this.flowArrows = null;
        this.updateQueue = [];
        this.isUpdating = false;
    }

    createPath(d, type) {
        const dx = d.target.x - d.source.x;
        const dy = d.target.y - d.source.y;
        const distance = Math.sqrt(dx * dx + dy * dy);
        
        // Calculer les points d'intersection avec les cercles des nœuds
        const sourceRadius = this.config.nodeRadius;
        const targetRadius = this.config.nodeRadius;
        
        // Calculer les points de départ et d'arrivée sur les bords des cercles
        const sourceX = d.source.x + (dx / distance) * sourceRadius;
        const sourceY = d.source.y + (dy / distance) * sourceRadius;
        const targetX = d.target.x - (dx / distance) * targetRadius;
        const targetY = d.target.y - (dy / distance) * targetRadius;

        switch(type) {

            case 'curves':
                const dr = Math.sqrt((targetX - sourceX) * (targetX - sourceX) + (targetY - sourceY) * (targetY - sourceY));
                return `M${sourceX},${sourceY}A${dr},${dr} 0 0,1 ${targetX},${targetY}`;

            case 'orthogonal':
                const midX = (sourceX + targetX) / 2;
                return `M${sourceX},${sourceY}L${midX},${sourceY}L${midX},${targetY}L${targetX},${targetY}`;

            default:
                return `M${sourceX},${sourceY}L${targetX},${targetY}`;
        }
    }

    getPointAndAngleOnPath(path, progress) {
        const pathElement = document.createElementNS("http://www.w3.org/2000/svg", "path");
        pathElement.setAttribute("d", path);
        const length = pathElement.getTotalLength();
        const point = pathElement.getPointAtLength(progress * length);
        
        // Calculer l'angle en utilisant un point légèrement plus loin sur le chemin
        const nextProgress = Math.min(progress + 0.01, 1);
        const nextPoint = pathElement.getPointAtLength(nextProgress * length);
        const angle = Math.atan2(nextPoint.y - point.y, nextPoint.x - point.x);
        
        return { x: point.x, y: point.y, angle };
    }

    calculateArrowPosition(path, progress, direction) {
        const adjustedProgress = direction === 1 ? progress : 1 - progress;
        const { x, y, angle } = this.getPointAndAngleOnPath(path, adjustedProgress);
        const rotationAngle = (angle * 180 / Math.PI) + (direction === 1 ? 0 : 180);

        return { x, y, rotationAngle };
    }

    createParallelPath(path, offset) {
        const pathElement = document.createElementNS("http://www.w3.org/2000/svg", "path");
        pathElement.setAttribute("d", path);
        const length = pathElement.getTotalLength();
        
        const points = [];

        for (let i = 0; i <= length; i += length / 20) {
            const point = pathElement.getPointAtLength(i);
            const nextPoint = pathElement.getPointAtLength(Math.min(i + 1, length));
            const angle = Math.atan2(nextPoint.y - point.y, nextPoint.x - point.x);
            points.push({
                x: point.x + Math.sin(angle) * offset,
                y: point.y - Math.cos(angle) * offset
            });
        }

        return `M${points[0].x},${points[0].y} ${points.slice(1).map(p => `L${p.x},${p.y}`).join(' ')}`;
    }

    createLinks() {
        // Vérifier si une mise à jour est en cours
        if (this.isUpdating) {
            this.updateQueue.push(() => this.performCreateLinks());
            return this.links;
        }

        return this.performCreateLinks();
    }

    performCreateLinks() {
        this.isUpdating = true;

        // Nettoyer complètement les éléments existants
        this.cleanupExistingElements();

        // Filtrer les liens invalides
        const validLinks = this.config.data.links.filter(link => {
            const source = typeof link.source === 'string' 
                ? this.config.data.nodes.find(n => n.id === link.source)
                : link.source;
            const target = typeof link.target === 'string'
                ? this.config.data.nodes.find(n => n.id === link.target)
                : link.target;
            return source && target;
        });

        const processedLinks = validLinks.flatMap(link => {
            const source = typeof link.source === 'string' 
                ? this.config.data.nodes.find(n => n.id === link.source)
                : link.source;
            const target = typeof link.target === 'string'
                ? this.config.data.nodes.find(n => n.id === link.target)
                : link.target;

            if (link.bidirectional) {
                return [
                    { ...link, source, target, direction: 1 },
                    { ...link, source, target, direction: -1 }
                ];
            } else {
                // Pour les liens unidirectionnels, utiliser la direction spécifiée
                const direction = link.direction || 1;
                return [{ ...link, source, target, direction }];
            }
        });

        // Mettre à jour la simulation
        if (this.simulation) {
            this.simulation.force("link").links(validLinks);
        }

        // Créer un nouveau groupe pour les liens
        const linkGroup = this.svg.append("g")
            .attr("class", "link-group");

        // Créer les nouveaux liens
        this.links = linkGroup
            .selectAll("path")
            .data(processedLinks)
            .join("path")
            .attr("class", d => `link ${d.direction === -1 ? 'reverse' : ''}`)
            .attr("stroke-width", d => Math.sqrt(d.value))
            .attr("stroke", "#666")
            .attr("fill", "none")
            .attr("stroke-dasharray", this.config.lineStyle === 'dashed' ? "5,5" : "none")
            .attr("stroke-dashoffset", d => d.direction === -1 ? 10 : 0);

        this.updateLinkStyle(this.config.linkType);

        // Recréer les flèches
        this.createFlowArrows();

        this.isUpdating = false;
        this.processUpdateQueue();

        return this.links;
    }

    cleanupExistingElements() {
        // Supprimer tous les éléments existants
        if (this.links) {
            this.links.remove();
            this.links = null;
        }
        if (this.flowArrows) {
            this.flowArrows.remove();
            this.flowArrows = null;
        }
        // Supprimer tous les groupes de liens existants
        this.svg.selectAll(".link-group").remove();
    }

    processUpdateQueue() {
        if (this.updateQueue.length > 0) {
            const nextUpdate = this.updateQueue.shift();
            nextUpdate();
        }
    }

    createFlowArrows() {
        this.flowArrows = this.svg.append("g")
            .selectAll("g")
            .data(this.config.data.links.flatMap(link => {
                if (link.bidirectional) {
                    return [
                        { ...link, direction: 1 },
                        { ...link, direction: -1 }
                    ];
                } else {
                    // Pour les liens unidirectionnels, utiliser la direction spécifiée
                    const direction = link.direction || 1;
                    return [{ ...link, direction }];
                }
            }))
            .join("g")
            .attr("class", d => `flow-arrow ${d.direction === -1 ? 'reverse' : ''}`);

        this.flowArrows.each((d, i, nodes) => {
            const g = d3.select(nodes[i]);
            const color = d.direction === -1 ? this.config.arrowColors.negative : this.config.arrowColors.positive;

            if (this.config.arrowStyle === 'arrow') {
                g.append("path")
                    .attr("d", "M-4,-2 L4,0 L-4,2 Z")
                    .attr("transform", `scale(${this.config.animation.arrowSize})`)
                    .attr("fill", color);
            } else {
                g.append("circle")
                    .attr("r", 3)
                    .attr("fill", color);
            }
        });

        return this.flowArrows;
    }

    updateLinkPositions() {
        if (!this.links) return;

        this.links.attr("d", d => {
            if (!d.source || !d.target) return "";
            return this.createPath(d, this.config.linkType);
        });
    }

    updateLinkStyle(type) {
        if (!this.links) return;

        this.config.linkType = type;
        this.updateLinkPositions();
    }

    updateFlowArrows() {
        if (!this.flowArrows) return;

        this.flowArrows.each((d, i, nodes) => {
            // Recalculer le chemin à chaque frame pour prendre en compte les nouvelles positions
            const path = this.createPath(d, this.config.linkType);
            const progress = (performance.now() % this.config.animation.duration) / this.config.animation.duration;
            const { x, y, rotationAngle } = this.calculateArrowPosition(path, progress, d.direction);
            
            const g = d3.select(nodes[i]);
            if (this.config.arrowStyle === 'arrow') {
                g.attr("transform", `translate(${x},${y}) rotate(${rotationAngle})`);
            } else if (this.config.arrowStyle === 'chevrons') {
                g.select(".chevron-group")
                    .attr("transform", `translate(${x},${y}) rotate(${rotationAngle})`);
            } else {
                g.attr("transform", `translate(${x},${y})`);
            }
        });
    }

    updateArrowStyle(style) {
        this.config.arrowStyle = style;
        if (this.flowArrows) {
            this.flowArrows.remove();
        }
        this.createFlowArrows();
    }

    updateLineStyle(style) {
        this.config.lineStyle = style;
        if (this.links) {
            this.links.attr("stroke-dasharray", style === 'dashed' ? "5,5" : "none");
        }
        if (this.flowArrows) {
            if (style === 'dashed') {
                this.flowArrows.style("display", "none");
            } else {
                this.flowArrows.style("display", "block");
                this.updateFlowArrows();
            }
        }
    }

    updateDashOffset() {
        if (this.links && this.config.lineStyle === 'dashed') {
            this.links.each((d, i, nodes) => {
                const path = d3.select(nodes[i]);
                const currentOffset = parseFloat(path.attr("stroke-dashoffset"));
                const newOffset = (currentOffset + (d.direction === -1 ? -1 : 1) * this.config.dashSpeed) % 10;
                path.attr("stroke-dashoffset", newOffset);
            });
        }
    }

    createLink(sourceId, targetId) {
        const newLink = {
            source: sourceId,
            target: targetId,
            value: 1,
            bidirectional: false
        };

        // Vérifier si le lien existe déjà
        const linkExists = this.config.data.links.some(link => 
            (link.source === sourceId && link.target === targetId) ||
            (link.source === targetId && link.target === sourceId)
        );

        if (!linkExists) {
            this.config.data.links.push(newLink);
            this.createLinks();
        }
    }

    updateArrowColors(color, direction) {
        // Mettre à jour la couleur dans le config
        this.config.arrowColors[direction] = color;
        
        // Supprimer les indicateurs existants et les recréer avec les nouvelles couleurs
        if (this.flowArrows) {
            this.flowArrows.remove();
        }
        this.createFlowArrows();
    }
} 
