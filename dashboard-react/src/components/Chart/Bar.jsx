import React, {Component} from 'react'
import {Bar} from 'react-chartjs-2'

class BarChart extends Component {

    render() {
        return (
            <Bar
                data={this.props.data}
                height={this.props.height}
                options={this.props.options}
            />
        )
    }
}

export default BarChart

BarChart.defaultChart = {
    height: 600,
}