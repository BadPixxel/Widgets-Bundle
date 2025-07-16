import * as d3 from "d3";
import { Node } from "../models/Node.js";
import { Link } from "../models/Link.js";

export class Graph {
  constructor(containerId, width = 800, height = 600) {
    this.container = d3.select(`#${containerId}`);
    this.width = width;
    this.height = height;
    this.nodes = new Map();
    this.links = new Map();
    this.simulation = null;

    this.init();
  }

  init() {
    // Create the SVG
    this.svg = this.container
      .append("svg")
      .attr("width", this.width)
      .attr("height", this.height);

    // Create groups for links and nodes
    this.linksGroup = this.svg.append("g").attr("class", "links");
    this.nodesGroup = this.svg.append("g").attr("class", "nodes");

    // Initialize the simulation
    this.initSimulation();
  }

  initSimulation() {
    this.simulation = d3
      .forceSimulation()
      .force(
        "link",
        d3
          .forceLink()
          .id((d) => d.id)
          .distance(100)
      )
      .force("charge", d3.forceManyBody().strength(-4000))
      .force("center", d3.forceCenter(this.width / 2, this.height / 2))
      .force(
        "collision",
        d3.forceCollide().radius((d) => d.radius + 10)
      );
  }

  addNode(id, x, y) {
    const node = new Node(id, x, y);
    this.nodes.set(id, node);
    return node;
  }

  addLink(sourceId, targetId) {
    const source = this.nodes.get(sourceId);
    const target = this.nodes.get(targetId);

    if (!source || !target) {
      console.error("Source or target node not found");
      return null;
    }

    const link = new Link(source, target);
    const linkId = `${sourceId}-${targetId}`;
    this.links.set(linkId, link);
    return link;
  }

  draw() {
    // Update links
    const linkElements = this.linksGroup
      .selectAll(".link")
      .data(Array.from(this.links.values()))
      .join("g")
      .attr("class", "link")
      .each(function (d) {
        // Create the line inside the group
        d3.select(this).append("line").attr("class", "link-line");
        // Draw the link (which now draws the line and indicator)
        d.draw(d3.select(this));
      });

    // Update nodes
    const nodeElements = this.nodesGroup
      .selectAll(".node")
      .data(Array.from(this.nodes.values()))
      .join("g")
      .attr("class", "node")
      .attr("id", (d) => `node-${d.id}`)
      .call(
        d3
          .drag()
          .on("start", this.dragstarted.bind(this))
          .on("drag", this.dragged.bind(this))
          .on("end", this.dragended.bind(this))
      );

    // Apply styles and draw nodes
    nodeElements.each(function (d) {
      d.draw(d3.select(this));
    });

    // Update the simulation
    this.simulation.nodes(Array.from(this.nodes.values())).on("tick", () => {
      // Update each link with the correct shape
      linkElements.each(function (d) {
        d.updatePath(d3.select(this));
      });

      nodeElements.attr("transform", (d) => `translate(${d.x},${d.y})`);
    });

    this.simulation.force("link").links(Array.from(this.links.values()));
  }

  dragstarted(event, d) {
    if (!event.active) this.simulation.alphaTarget(0.3).restart();
    d.fx = d.x;
    d.fy = d.y;
  }

  dragged(event, d) {
    d.fx = event.x;
    d.fy = event.y;
  }

  dragended(event, d) {
    if (!event.active) this.simulation.alphaTarget(0);
    d.fx = null;
    d.fy = null;
  }

  resize(width, height) {
    this.width = width;
    this.height = height;

    // Update the SVG size
    this.svg.attr("width", width).attr("height", height);

    // Update the center force
    this.simulation.force("center", d3.forceCenter(width / 2, height / 2));

    // Restart the simulation
    this.simulation.alpha(0.3).restart();
  }
}
