import React, {Component} from 'react'
import MasterLayout from '../../components/layouts/Master';
import SmallBox from '../../components/SmallBox/SmallBox';
import DoughnutChart from '../../components/Chart/Doughnut';
import {Card, CardBody, CardTitle} from '../../components/Card/Card';
import BarChart from '../../components/Chart/Bar';
import api from '../../services/api';
import {ApiRouteList} from '../../services/routes';

class Painel extends Component {

    constructor() {
        super();

        this.state = {
            smallBoxes: [],
            doughnutChart: [],
            barChart: [],
            loaderActive: true
        }

    }

    componentDidMount() {

        api.get(ApiRouteList.dashboardGetInformations)
            .then((response) => {

                if (response.data.error) {
                    throw response;
                }

                this.setState({
                    smallBoxes: response.data.content.smallBoxes,
                    doughnutChart: response.data.content.doughnutChart,
                    barChart: response.data.content.barChart,
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

                        <div className="row">

                            {
                                this.state.smallBoxes.map((item) => (
                                    <SmallBox
                                        value={item.value}
                                        title={item.title}
                                        colorBox={item.colorBox}
                                        iconBox={item.iconBox}
                                    />
                                ))
                            }

                        </div>

                        <div className="row">

                            {
                                this.state.doughnutChart.map((item) => (
                                    <div className="col-md-12 col-lg-6 col-xl-3">
                                        <Card>

                                            <CardTitle>
                                                {item.title}
                                            </CardTitle>

                                            <CardBody>

                                                <DoughnutChart
                                                    options={item.options}
                                                    data={item.data}
                                                />

                                            </CardBody>
                                        </Card>
                                    </div>
                                ))
                            }

                            {
                                this.state.barChart.map((item) => (
                                    <div className="col-lg-12 col-xl-6">
                                        <Card>

                                            <CardTitle>
                                                {item.title}
                                            </CardTitle>

                                            <CardBody>

                                                <BarChart
                                                    options={item.options}
                                                    data={item.data}
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

export default Painel