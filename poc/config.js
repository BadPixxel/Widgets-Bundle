export const DEFAULT_CONFIG = {
    width: 1200,
    height: 800,
    nodeRadius: 30,
    linkDistance: 150,
    linkType: 'orthogonal',
    colors: {
        primary: "#4CAF50",
        database: "#2196F3",
        web: "#FFC107",
        cache: "#9C27B0",
        loadbalancer: "#FF5722",
        backup: "#607D8B",
        proxy: "#795548",
        storage: "#E91E63"
    },
    animation: {
        duration: 3000,
        arrowSize: 1.5
    },
    forces: {
        linkStrength: 0.7,
        chargeStrength: -400,
        collisionRadius: 60,
        centerStrength: 0.1
    }
};

export const SERVER_TYPES = [
    { value: "primary", label: "Principal" },
    { value: "database", label: "Base de données" },
    { value: "web", label: "Serveur Web" },
    { value: "cache", label: "Cache" },
    { value: "loadbalancer", label: "Load Balancer" },
    { value: "backup", label: "Backup" },
    { value: "proxy", label: "Proxy" },
    { value: "storage", label: "Stockage" }
];

export const LINK_TYPES = [
    { value: "orthogonal", label: "Orthogonales" },
    { value: "curves", label: "Courbes" },
    { value: "lines", label: "Lignes droites" }
]; 
