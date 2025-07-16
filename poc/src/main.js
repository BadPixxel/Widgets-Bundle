import { Graph } from './js/components/Graph.js';

document.addEventListener('DOMContentLoaded', () => {
    const graphDataScript = document.getElementById('graph-data');
    let graph;
    if (graphDataScript) {
        let config;
        try {
            config = JSON.parse(graphDataScript.textContent);
        } catch (e) {
            throw e;
        }
        graph = new Graph('graph-container', window.innerWidth, window.innerHeight);
        window.addEventListener('resize', () => {
            graph.resize(window.innerWidth, window.innerHeight);
        });
        // Ajout des nœuds
        (config.nodes || []).forEach(nodeCfg => {
            const node = graph.addNode(nodeCfg.id, nodeCfg.x, nodeCfg.y);
            if (nodeCfg.label) node.setLabel(nodeCfg.label);
            if (nodeCfg.color) node.setColor(nodeCfg.color);
            if (nodeCfg.radius) node.setRadius(nodeCfg.radius);
            if (nodeCfg.image) node.setImage(nodeCfg.image, nodeCfg.imageSize);
        });
        // Ajout des liens
        (config.links || []).forEach(linkCfg => {
            const link = graph.addLink(linkCfg.source, linkCfg.target);
            if (linkCfg.strokeWidth) link.setStrokeWidth(linkCfg.strokeWidth);
            if (linkCfg.strokeColor) link.setStrokeColor(linkCfg.strokeColor);
            if (linkCfg.lineStyle) link.setLineStyle(linkCfg.lineStyle);
            if (linkCfg.curveType) link.setCurveType(linkCfg.curveType, { strength: linkCfg.curveStrength });
            if (linkCfg.indicator) link.setIndicator(linkCfg.indicator.type, linkCfg.indicator);
        });
        graph.draw();
    }
});
