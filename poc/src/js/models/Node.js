export class Node {
    constructor(id, x = 0, y = 0) {
        this.id = id;
        this.x = x;
        this.y = y;
        this.radius = 10;
        this.color = '#1f77b4';
        this.label = '';
        this.data = {};
        this.image = null;
        this.imageSize = 20; // Taille par défaut de l'image
    }

    // Méthodes pour personnaliser l'apparence
    setRadius(radius) {
        this.radius = radius;
        return this;
    }

    setColor(color) {
        this.color = color;
        return this;
    }

    setLabel(label) {
        this.label = label;
        return this;
    }

    setImage(imageUrl, size = 20) {
        this.image = imageUrl;
        this.imageSize = size;
        return this;
    }

    // Méthode pour dessiner le nœud
    draw(selection) {
        // Ajout du cercle
        selection.append('circle')
            .attr('r', this.radius)
            .attr('fill', this.color);

        // Ajout de l'image si elle est définie
        if (this.image) {
            const imageSize = this.imageSize;
            selection.append('image')
                .attr('x', -imageSize/2)
                .attr('y', -imageSize/2)
                .attr('width', imageSize)
                .attr('height', imageSize)
                .attr('xlink:href', this.image);
        }

        // Ajout du texte si un label est défini
        if (this.label) {
            selection.append('text')
                .text(this.label)
                .attr('dy', this.radius + 15)
                .attr('text-anchor', 'middle');
        }

        return selection;
    }

    // Méthode pour mettre à jour la position
    updatePosition(x, y) {
        this.x = x;
        this.y = y;
    }
} 
