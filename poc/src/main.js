import { count } from 'd3';
import { Graph } from './js/components/Graph.js';

// Création du graphe avec les dimensions de la fenêtre
const graph = new Graph('graph-container', window.innerWidth, window.innerHeight);

// Gestion du redimensionnement de la fenêtre
window.addEventListener('resize', () => {
    graph.resize(window.innerWidth, window.innerHeight);
});

// Ajout de quelques nœuds
const node1 = graph.addNode('1', 100, 100)
    .setLabel('Nœud 1')
    .setColor('#ff7f0e')
    .setRadius(15)
    .setImage('https://cdn-icons-png.flaticon.com/512/149/149071.png', 25);

const node2 = graph.addNode('2', 200, 200)
    .setLabel('Nœud 2')
    .setColor('#2ca02c')
    .setRadius(20);

const node3 = graph.addNode('3', 300, 150)
    .setLabel('Nœud 3')
    .setColor('#d62728')
    .setRadius(40)
    .setImage('https://upload.wikimedia.org/wikipedia/commons/0/0e/Shopify_logo_2018.svg', 60);

const node4 = graph.addNode('4', 400, 200)
    .setLabel('Nœud 4')
    .setColor('#9467bd')
    .setRadius(50);

// Ajout de quelques liens
const link1 = graph.addLink('1', '2')
    .setStrokeWidth(30)
    .setStrokeColor('#00bcd4')
    .setIndicator('chevron', { color: '#d62728', speed: 1.5, count: 5 });

const link2 = graph.addLink('2', '3')
    .setStrokeWidth(2)
    .setStrokeColor('#999')
    .setLineStyle('dashed')
    .setCurveType('curved', { strength: 50 })
    .setIndicator('dot', { color: '#00ff00', size: 6, speed: 1 });

// Ajout d'un lien pointillé
const link3 = graph.addLink('3', '4')
    .setStrokeWidth(10)
    .setStrokeColor('#9467bd')
    .setLineStyle('dashed')
    .setCurveType('orthogonal');

// Dessin du graphe
graph.draw(); 
