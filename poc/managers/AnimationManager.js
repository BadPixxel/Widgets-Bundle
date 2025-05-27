export class AnimationManager {
    constructor(config, linkManager) {
        this.config = config;
        this.linkManager = linkManager;
        this.animationFrame = null;
    }

    startArrowAnimation() {
        const animate = () => {
            this.linkManager.updateFlowArrows();
            this.animationFrame = requestAnimationFrame(animate);
        };
        this.animationFrame = requestAnimationFrame(animate);
    }

    startDashAnimation() {
        const animate = () => {
            if (this.config.lineStyle === 'dashed') {
                this.linkManager.updateDashOffset();
                this.animationFrame = requestAnimationFrame(animate);
            }
        };
        this.animationFrame = requestAnimationFrame(animate);
    }

    stopAnimation() {
        if (this.animationFrame) {
            cancelAnimationFrame(this.animationFrame);
            this.animationFrame = null;
        }
    }

    updateDashSpeed(speed) {
        this.config.dashSpeed = speed;
    }
} 
