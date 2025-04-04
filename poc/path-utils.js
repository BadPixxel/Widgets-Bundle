export class PathUtils {
    static createPath(d, type) {
        const dx = d.target.x - d.source.x;
        const dy = d.target.y - d.source.y;
        
        switch(type) {
            case 'curves':
                const dr = Math.sqrt(dx * dx + dy * dy);
                return `M${d.source.x},${d.source.y}A${dr},${dr} 0 0,1 ${d.target.x},${d.target.y}`;
            case 'orthogonal':
                const midX = (d.source.x + d.target.x) / 2;
                return `M${d.source.x},${d.source.y}L${midX},${d.source.y}L${midX},${d.target.y}L${d.target.x},${d.target.y}`;
            default:
                return `M${d.source.x},${d.source.y}L${d.target.x},${d.target.y}`;
        }
    }

    static getPointAndAngleOnPath(path, progress) {
        const pathElement = document.createElementNS("http://www.w3.org/2000/svg", "path");
        pathElement.setAttribute("d", path);
        const length = pathElement.getTotalLength();
        const point = pathElement.getPointAtLength(progress * length);
        
        const nextPoint = pathElement.getPointAtLength(Math.min((progress + 0.01) * length, length));
        const angle = Math.atan2(nextPoint.y - point.y, nextPoint.x - point.x);
        
        return { x: point.x, y: point.y, angle };
    }

    static calculateArrowPosition(path, progress, direction) {
        const adjustedProgress = direction === 1 ? progress : 1 - progress;
        const { x, y, angle } = this.getPointAndAngleOnPath(path, adjustedProgress);
        const rotationAngle = (angle * 180 / Math.PI) + (direction === 1 ? 0 : 180);
        return { x, y, rotationAngle };
    }
} 
