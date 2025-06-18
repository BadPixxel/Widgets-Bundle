import * as d3 from 'd3';

export class Link {
    constructor(source, target) {
        this.source = source;
        this.target = target;
        this.strokeWidth = 2;
        this.strokeColor = '#999';
        this.lineStyle = 'solid';
        this.data = {};
        this.animationId = null;
        this.indicatorType = null; // 'arrow', 'dot', etc.
        this.indicatorColor = '#666';
        this.indicatorSize = 10;
        this.indicatorSpeed = 1;
        this.indicatorAnimationId = null;
        this.indicatorCount = 4; // Par défaut 4 chevrons
        this.curveType = 'straight'; // 'straight', 'curved', 'orthogonal'
        this.curveStrength = 50; // Pour les courbes, distance de la ligne droite
    }

    // Méthodes pour personnaliser l'apparence
    setStrokeWidth(width) {
        this.strokeWidth = width;
        return this;
    }

    setStrokeColor(color) {
        this.strokeColor = color;
        return this;
    }

    setLineStyle(style) {
        if (style !== 'solid' && style !== 'dashed') {
            console.warn('Invalid line style. Using solid as default.');
            style = 'solid';
        }
        this.lineStyle = style;
        return this;
    }

    // Nouvelles méthodes pour les indicateurs
    setIndicator(type, options = {}) {
        this.indicatorType = type;
        this.indicatorColor = options.color || this.indicatorColor;
        // Taille auto : 80% de l'épaisseur du lien si non précisé
        if (typeof options.size === 'number') {
            this.indicatorSize = options.size;
        } else {
            this.indicatorSize = this.strokeWidth * 0.8;
        }
        this.indicatorSpeed = options.speed || this.indicatorSpeed;
        this.indicatorCount = options.count || 4; // Par défaut 4 chevrons
        return this;
    }

    removeIndicator() {
        this.indicatorType = null;
        if (this.indicatorAnimationId) {
            cancelAnimationFrame(this.indicatorAnimationId);
            this.indicatorAnimationId = null;
        }
        return this;
    }

    setCurveType(type, options = {}) {
        if (!['straight', 'curved', 'orthogonal'].includes(type)) {
            console.warn('Invalid curve type. Using straight as default.');
            type = 'straight';
        }
        this.curveType = type;
        this.curveStrength = options.strength || this.curveStrength;
        return this;
    }

    // Méthode pour dessiner le lien
    draw(selection) {
        // Supprimer l'ancienne ligne si elle existe
        selection.selectAll('.link-line').remove();

        // Créer un nouveau path
        const line = selection.append('path')
            .attr('class', 'link-line')
            .attr('stroke-width', this.strokeWidth)
            .attr('stroke', this.strokeColor)
            .attr('fill', 'none');

        let d;
        if (this.curveType === 'straight') {
            d = `M${this.source.x},${this.source.y} L${this.target.x},${this.target.y}`;
        } else if (this.curveType === 'curved') {
            const midX = (this.source.x + this.target.x) / 2;
            const midY = (this.source.y + this.target.y) / 2;
            const angle = Math.atan2(this.target.y - this.source.y, this.target.x - this.source.x);
            const perpX = midX + Math.cos(angle + Math.PI/2) * this.curveStrength;
            const perpY = midY + Math.sin(angle + Math.PI/2) * this.curveStrength;
            d = `M${this.source.x},${this.source.y} Q${perpX},${perpY} ${this.target.x},${this.target.y}`;
        } else if (this.curveType === 'orthogonal') {
            const midX = (this.source.x + this.target.x) / 2;
            d = `M${this.source.x},${this.source.y} L${midX},${this.source.y} L${midX},${this.target.y} L${this.target.x},${this.target.y}`;
        }

        line.attr('d', d);

        if (this.lineStyle === 'dashed') {
            line.attr('stroke-dasharray', '5');
            
            if (this.animationId) {
                cancelAnimationFrame(this.animationId);
            }

            let offset = 0;
            const animate = () => {
                offset = (offset - 1 + 10) % 10;
                line.attr('stroke-dashoffset', offset);
                this.animationId = requestAnimationFrame(animate);
            };
            this.animationId = requestAnimationFrame(animate);
        } else {
            line.attr('stroke-dasharray', null)
                .attr('stroke-dashoffset', null);
            
            if (this.animationId) {
                cancelAnimationFrame(this.animationId);
                this.animationId = null;
            }
        }

        // Gestion de l'indicateur
        if (this.indicatorType) {
            // Supprimer l'ancien indicateur s'il existe
            selection.selectAll('.indicator').remove();

            // Créer le nouvel indicateur
            const indicator = selection.append('g')
                .attr('class', 'indicator');

            if (this.indicatorType === 'arrow') {
                indicator.append('path')
                    .attr('d', `M-${this.indicatorSize},-${this.indicatorSize/2} L0,0 L-${this.indicatorSize},${this.indicatorSize/2}`)
                    .attr('fill', 'none')
                    .attr('stroke', this.indicatorColor)
                    .attr('stroke-width', 2);
            } else if (this.indicatorType === 'dot') {
                indicator.append('circle')
                    .attr('r', this.indicatorSize/2)
                    .attr('fill', this.indicatorColor);
            } else if (this.indicatorType === 'chevron') {
                // Ajout de plusieurs chevrons
                for (let i = 0; i < this.indicatorCount; i++) {
                    indicator.append('path')
                        .attr('class', 'chevron')
                        .attr('d', this.getChevronPath())
                        .attr('fill', 'none')
                        .attr('stroke', this.indicatorColor)
                        .attr('stroke-width', 2);
                }
            }

            // Animation de l'indicateur
            let progress = 0;
            const animateIndicator = () => {
                progress = (progress + this.indicatorSpeed * 0.01) % 1;
                if (this.indicatorType === 'chevron') {
                    // Animation de chaque chevron
                    const chevrons = indicator.selectAll('.chevron');
                    chevrons.each((d, i, nodes) => {
                        // Décalage pour chaque chevron
                        const offset = ((progress) + (i / this.indicatorCount)) % 1;
                        const pos = this.getPositionAtProgress(offset);
                        d3.select(nodes[i])
                            .attr('transform', `translate(${pos.x},${pos.y}) rotate(${pos.angle})`);
                    });
                } else {
                    const pos = this.getPositionAtProgress(progress);
                    indicator.attr('transform', `translate(${pos.x},${pos.y}) rotate(${pos.angle})`);
                }
                this.indicatorAnimationId = requestAnimationFrame(animateIndicator);
            };
            this.indicatorAnimationId = requestAnimationFrame(animateIndicator);
        } else if (this.indicatorAnimationId) {
            cancelAnimationFrame(this.indicatorAnimationId);
            this.indicatorAnimationId = null;
            selection.selectAll('.indicator').remove();
        }

        return line;
    }

    // Méthode pour calculer la position de l'indicateur
    getPositionAtProgress(progress) {
        // Helper pour clamp progress
        function clamp(val, min, max) {
            return Math.max(min, Math.min(max, val));
        }
        const t = clamp(progress, 0, 1);
        const p = this._pointAtProgress(t);
        let angle = 0;

        if (this.curveType === 'curved') {
            // Dérivée analytique de la courbe de Bézier quadratique
            const midX = (this.source.x + this.target.x) / 2;
            const midY = (this.source.y + this.target.y) / 2;
            const angleBase = Math.atan2(this.target.y - this.source.y, this.target.x - this.source.x);
            const perpX = midX + Math.cos(angleBase + Math.PI/2) * this.curveStrength;
            const perpY = midY + Math.sin(angleBase + Math.PI/2) * this.curveStrength;

            // Dérivée de Bézier quadratique
            const dx = 2*(1-t)*(perpX - this.source.x) + 2*t*(this.target.x - perpX);
            const dy = 2*(1-t)*(perpY - this.source.y) + 2*t*(this.target.y - perpY);
            angle = Math.atan2(dy, dx) * 180 / Math.PI;
        } else if (this.curveType === 'straight') {
            angle = Math.atan2(this.target.y - this.source.y, this.target.x - this.source.x) * 180 / Math.PI;
        } else if (this.curveType === 'orthogonal') {
            // Pour orthogonal, on peut garder l'approximation numérique
            const delta = 0.0001;
            const p1 = this._pointAtProgress(clamp(t - delta, 0, 1));
            const p2 = this._pointAtProgress(clamp(t + delta, 0, 1));
            angle = Math.atan2(p2.y - p1.y, p2.x - p1.x) * 180 / Math.PI;
        }

        return { x: p.x, y: p.y, angle };
    }

    // Nouvelle méthode utilitaire pour obtenir la position exacte sur la courbe
    _pointAtProgress(progress) {
        let x, y;
        if (this.curveType === 'straight') {
            x = this.source.x + (this.target.x - this.source.x) * progress;
            y = this.source.y + (this.target.y - this.source.y) * progress;
        } else if (this.curveType === 'curved') {
            // Quadratic Bezier approx (source, control, target)
            const midX = (this.source.x + this.target.x) / 2;
            const midY = (this.source.y + this.target.y) / 2;
            const angle = Math.atan2(this.target.y - this.source.y, this.target.x - this.source.x);
            const perpX = midX + Math.cos(angle + Math.PI/2) * this.curveStrength;
            const perpY = midY + Math.sin(angle + Math.PI/2) * this.curveStrength;
            // Quadratic Bezier formula
            const t = progress;
            x = (1-t)*(1-t)*this.source.x + 2*(1-t)*t*perpX + t*t*this.target.x;
            y = (1-t)*(1-t)*this.source.y + 2*(1-t)*t*perpY + t*t*this.target.y;
        } else if (this.curveType === 'orthogonal') {
            const midX = (this.source.x + this.target.x) / 2;
            if (progress < 0.33) {
                const t = progress * 3;
                x = this.source.x + (midX - this.source.x) * t;
                y = this.source.y;
            } else if (progress < 0.66) {
                const t = (progress - 0.33) * 3;
                x = midX;
                y = this.source.y + (this.target.y - this.source.y) * t;
            } else {
                const t = (progress - 0.66) * 3;
                x = midX + (this.target.x - midX) * t;
                y = this.target.y;
            }
        }
        return { x, y };
    }

    // Méthode pour obtenir le path SVG d'un chevron
    getChevronPath() {
        const s = this.indicatorSize;
        // Chevron centré sur (0,0), ouvert vers la droite
        return `M${-s/2},${-s/2} L0,0 L${-s/2},${s/2}`;
    }

    // Méthode pour mettre à jour la position du lien
    updatePosition() {
        return {
            x1: this.source.x,
            y1: this.source.y,
            x2: this.target.x,
            y2: this.target.y
        };
    }

    // Met à jour le path existant selon la position courante des nœuds
    updatePath(selection) {
        let d;
        if (this.curveType === 'straight') {
            d = `M${this.source.x},${this.source.y} L${this.target.x},${this.target.y}`;
        } else if (this.curveType === 'curved') {
            const midX = (this.source.x + this.target.x) / 2;
            const midY = (this.source.y + this.target.y) / 2;
            const angle = Math.atan2(this.target.y - this.source.y, this.target.x - this.source.x);
            const perpX = midX + Math.cos(angle + Math.PI/2) * this.curveStrength;
            const perpY = midY + Math.sin(angle + Math.PI/2) * this.curveStrength;
            d = `M${this.source.x},${this.source.y} Q${perpX},${perpY} ${this.target.x},${this.target.y}`;
        } else if (this.curveType === 'orthogonal') {
            const midX = (this.source.x + this.target.x) / 2;
            d = `M${this.source.x},${this.source.y} L${midX},${this.source.y} L${midX},${this.target.y} L${this.target.x},${this.target.y}`;
        }
        selection.select('.link-line').attr('d', d);
    }
} 
