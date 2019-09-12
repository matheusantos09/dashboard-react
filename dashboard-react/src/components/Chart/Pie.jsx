import React, {Component} from 'react'
import {Pie} from 'react-chartjs-2'

class PieChart extends Component {

    render() {
        return (
            <Pie
                data={this.props.data}
                height={this.props.height}
                options={this.props.options}
            />
        )
    }
}

export default PieChart

PieChart.defaultChart = {
    height: 600,
}