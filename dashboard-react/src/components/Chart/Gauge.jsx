import React, {Component} from 'react'
import ReactSpeedometer from "react-d3-speedometer"

class GaugeChart extends Component {

    render() {
        return (
            <div>
                <div className="gauge-chart">
                    <ReactSpeedometer
                        value={this.props.value}
                        minValue={this.props.minValue}
                        maxValue={this.props.maxValue}
                        needleColor={this.props.needleColor}
                        startColor={this.props.startColor}
                        endColor={this.props.endColor}
                        ringWidth={this.props.ringWidth}
                        currentValueText="#{value} Hrs"
                        currentValuePlaceholderStyle={"#{value}"}
                        fluidWidth={true}
                        forceRender={true}
                        segments={this.props.segments}
                        maxSegmentLabels={this.props.maxSegmentLabels}
                        customSegmentStops={this.props.customSegmentStops}
                    />
                </div>
            </div>
        )
    }
}

GaugeChart.defaultProps = {
    value: 0,
    minValue: 0,
    maxValue: 100,
    needleColor: '#000',
    startColor: '#6ad72d',
    endColor: '#ff1700',
    ringWidth: 50,
    customSegmentStops: [],
    maxSegmentLabels: 5,
    segments: 50,
}

export default GaugeChart