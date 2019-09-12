import React, {Component} from 'react'
import {Doughnut} from 'react-chartjs-2'

class DoughnutChart extends Component {

    render() {
        return (
            <Doughnut
                data={this.props.data}
                height={this.props.height}
                options={this.props.options}
            />
        )
    }
}

export default DoughnutChart

DoughnutChart.defaultProps = {
    height: 400,
}