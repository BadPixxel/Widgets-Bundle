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
        this.imageSize = 20; // Default image size
    }

    // Methods to customize appearance
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

    // Method to draw the node
    draw(selection) {
        // Add the circle
        selection.append('circle')
            .attr('r', this.radius)
            .attr('fill', this.color);

        // Add the image if defined
        if (this.image) {
            const imageSize = this.imageSize;
            selection.append('image')
                .attr('x', -imageSize/2)
                .attr('y', -imageSize/2)
                .attr('width', imageSize)
                .attr('height', imageSize)
                .attr('xlink:href', this.image);
        }

        // Add the text if a label is defined
        if (this.label) {
            selection.append('text')
                .text(this.label)
                .attr('dy', this.radius + 15)
                .attr('text-anchor', 'middle');
        }

        return selection;
    }

    // Method to update the position
    updatePosition(x, y) {
        this.x = x;
        this.y = y;
    }
} 
