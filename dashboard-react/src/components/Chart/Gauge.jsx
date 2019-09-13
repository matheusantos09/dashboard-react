import React, {Component} from 'react'
import ReactSpeedometer from "react-d3-speedometer"

class GaugeChart extends Component {

    constructor() {
        super();

        this.state = {
            height: 300,
        };
        this.handleResize = this.handleResize.bind(this);
    }

    handleResize() {

        var currentWidth = window.innerWidth;

        var breaks = [];
        breaks[1440] = 260
        breaks[1400] = 220
        breaks[1200] = 200
        breaks[1024] = 180
        breaks[768] = 250
        breaks[550] = 270
        breaks[425] = 240
        breaks[375] = 200
        breaks[320] = 150

        // eslint-disable-next-line
        breaks.map((item, index) => {
            if (index <= currentWidth) {
                this.setState({
                    height: item,
                })
                return true;
            }
        })

    }

    componentDidMount() {
        this.handleResize()
    }

    UNSAFE_componentWillMount() {
        window.addEventListener('resize', this.handleResize)
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize)
    }

    render() {
        return (
            <div>
                <div style={{height: this.state.height,textAlign:'center'}}>
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