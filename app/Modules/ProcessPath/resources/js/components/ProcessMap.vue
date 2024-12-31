<template>
    <div>
        <svg width="100%" height="220">
            <g></g>
        </svg>
    </div>
</template>

<script>
import * as d3 from 'd3'
import dagreD3 from "dagre-d3";
export default {
    props: {
        encoded_process_type_id: '',
        encoded_ref_id: '',
        cat_id: ''
    },
    data() {
        return {}
    },
    created() {
        this.getProcessMapInfo();
    },
    methods: {
        getProcessMapInfo() {
            var app = this;
            axios.get('/process/graph/' + app.encoded_process_type_id + '/' + app.encoded_ref_id + '/' + app.cat_id).then(response => {
                this.drawProcessMap(response.data);
            }).catch(function (error) {
                // handle error
                console.log(error);
            })
                .then(function () {
                    // always executed

                });
        },
        drawProcessMap(response) {

            const svg = d3.select("svg"),
                inner = svg.select("g");
            const zoom = d3.zoom().on("zoom", function () {
                inner.attr("transform", d3.event.transform);
            });

            svg.call(zoom);
            // Create the renderer
            const render = new dagreD3.render();
            const g = new dagreD3.graphlib.Graph().setGraph({});


            let i = 0;
            let finalResult = true;
            const message = '';

            // modify desk list
            response.desks.forEach(function (desk) {
                if (i === 0) {
                    if (response.passed_status_id[i] == 5) { //5= shortfall
                        finalResult = false;
                    }
                }

                g.setNode(desk.name, { label: desk.label });
                if (response.passed_desks_id.indexOf(desk.desk_id) != -1 && finalResult === true) {
                    g.node(desk.name).style = "fill: orange";
                } else {
                    if (desk.desk_id == 0)
                        g.node(desk.name).style = "fill: orange";
                    else
                        g.node(desk.name).style = "fill: #666";
                }
            });

            // modify desk-status list
            i = 0;
            response.desk_action.forEach(function (action) {
                if (i === 0) {
                    if (response.passed_status_id[i] == 5) { //5= shortfall
                        finalResult = false;
                    }
                }

                g.setNode(action.name, { label: action.label, shape: action.shape });
                if (response.passed_status_id.indexOf(action.action_id) != -1 && finalResult === true) {
                    if (action.action_id === 5) {
                        g.node(action.name).style = "fill: #666";
                    } else {
                        g.node(action.name).style = "fill: orange";
                    }
                } else {
                    g.node(action.name).style = "fill: #666";
                }

                i++
            });

            response.edge_path.forEach((edge) => {
                g.setEdge.apply(g, edge);
            });

            // Set the rankdir
            g.graph().rankdir = "LR";
            g.graph().nodesep = 60;

            // Set some general styles
            g.nodes().forEach(function (v) {
                const node = g.node(v);
                node.rx = node.ry = 5;
            });
            render(inner, g);
        }
    }
}
</script>

<style>
svg {
    overflow: hidden;
    cursor: pointer;
    margin: 0 auto;
}

.node rect {
    stroke: #333;
    fill: #fff;
}

.edgePath path {
    stroke: #333;
    fill: #333;
    stroke-width: 1.5px;
}
</style>