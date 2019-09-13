import React, {Component} from 'react'
import MasterLayout from '../../components/layouts/Master';
import GaugeChart from '../../components/Chart/Gauge';
import {Card, CardBody, CardTitle} from "../../components/Card/Card";
import api from "../../services/api";
import {ApiRouteList} from "../../services/routes";

class AnalyticActive extends Component {

    constructor() {
        super();

        this.state = {
            loaderActive: true,
            gaugeChart: []
        }

    }

    componentDidMount() {

        api.get(ApiRouteList.analyticProjectActive)
            .then((response) => {

                if (response.data.error) {
                    throw response;
                }

                this.setState({
                    gaugeChart: response.data.content,
                })


            })
            .catch((error) => {
                console.log('CATCH');
                console.log(error);
            });

        this.setState({
            loaderActive: false
        })
    }

    render() {
        return (
            <MasterLayout
                loaderActive={this.state.loaderActive}
            >

                <div className="grid-chart">
                    {
                        this.state.gaugeChart.map((item, index) => (
                            <div key={index} className="item-chart">
                                <Card>

                                    <CardTitle>
                                        {item.title}
                                    </CardTitle>

                                    <CardBody>
                                        <GaugeChart
                                            value={item.value}
                                            minValue={item.minValue}
                                            maxValue={item.maxValue}
                                        />
                                    </CardBody>

                                </Card>
                            </div>
                        ))
                    }
                </div>

            </MasterLayout>
        )
    }
}

export default AnalyticActive