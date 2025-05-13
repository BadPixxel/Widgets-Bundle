export class TooltipManager {
    constructor() {
        this.tooltip = d3.select(".tooltip");
    }

    setupTooltips(nodes) {
        nodes
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
} 
