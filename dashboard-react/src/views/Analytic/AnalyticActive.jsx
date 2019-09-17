import React, {Component} from 'react'
import MasterLayout from '../../components/layouts/Master';
import GaugeChart from '../../components/Chart/Gauge';
import {Card, CardBody, CardTitle} from "../../components/Card/Card";
import api from "../../services/api";
import {ApiRouteList} from "../../services/routes";
import Select from 'react-select';
import makeAnimated from 'react-select/animated';
import {notify} from "../../components/Notification/Notify";
import {HandleErrorNotify} from "../../services/handleException";

const animatedComponents = makeAnimated()

const MAIOR_TEMPO_ESTIMADO = 'MAIOR_TEMPO_ESTIMADO'
const MENOR_TEMPO_ESTIMADO = 'MENOR_TEMPO_ESTIMADO'
const MAIOR_TEMPO_GASTO = 'MAIOR_TEMPO_GASTO'
const MENOR_TEMPO_GASTO = 'MENOR_TEMPO_GASTO'

class AnalyticActive extends Component {

    constructor() {
        super();

        this.state = {
            loaderActive: true,
            gaugeChart: [],
            selectedOption: [],
            options: [],
            filterName: '',

        }

        this.handleFileNameForm = this.handleFileNameForm.bind(this)
        this.changeOrder = this.changeOrder.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)

    }

    handleChange = newSelects => {

        let oldSelects = this.state.selectedOption

        this.setState({
            selectedOption: newSelects
        });

        let charts = this.state.gaugeChart;

        if (!oldSelects) {
            oldSelects = []
        }

        /*
        * Caso nao tenha nenhum novo todos serão mostrados
        * */
        if (newSelects) {

            let onlyInA = oldSelects.filter(this.comparer(newSelects));
            let onlyInB = newSelects.filter(this.comparer(oldSelects));

            let resultArray = onlyInA.concat(onlyInB);

            if (resultArray.length) {
                charts.map((itemC) => (
                    itemC.key === resultArray[0].value ? itemC.showBlock = !itemC.showBlock : null
                ))
            }

        } else {
            this.showAllCharts();
        }


        this.setState({
            gaugeChart: charts,
        })

        return newSelects
    }

    comparer(otherArray, key = false) {
        if (!key) {
            return function (current) {
                return otherArray.filter(function (other) {
                    return other.value === current.value
                }).length === 0;
            }
        }

        return function (current) {
            return otherArray.filter(function (other) {
                return other.value === current.key
            }).length === 0;
        }

    }

    showAllCharts() {
        let charts = this.state.gaugeChart;

        charts.map((itemC) => (
            itemC.showBlock = true
        ))

        this.setState({
            gaugeChart: charts
        });
    }

    handleFileNameForm({target}) {
        let name, filter, consult;

        name = target.value
        consult = this.state.gaugeChart
        filter = name.toUpperCase()

        consult.map((item) => (
            item.filter = item.title.toUpperCase().indexOf(filter) > -1
        ))

        this.setState({
            gaugeChart: consult
        });

    }

    //@TODO Corrigir o botao de ordenação
    changeOrder({target}) {

        let value = target.value
        let list = this.state.gaugeChart
        console.log(list)

        switch (value) {

            case MAIOR_TEMPO_ESTIMADO:


                // let i, switching, newList, shouldSwitch;
                //
                // switching = true;
                //
                // while (switching) {
                //     switching = false;
                //
                //     console.log(list.length)
                //
                //     for (i = 0; i < (list.length - 1); i++) {
                //         shouldSwitch = false;
                //
                //     //     if (parseFloat(list[i].maxValue) < parseFloat(list[i + 1].maxValue)) {
                //     //         shouldSwitch = true;
                //     //         break;
                //     //     }
                //
                //     }
                //     //
                //     // if (shouldSwitch) {
                //     //     newList = list[i]
                //     //     switching = true;
                //     // }
                // }
                //
                // // this.setState({
                // //     gaugeChart: newList
                // // })

                // console.log(newList)

                break

            case
            MENOR_TEMPO_ESTIMADO:

                break

            case
            MAIOR_TEMPO_GASTO:

                break

            case
            MENOR_TEMPO_GASTO:

                break

            default:


                break

        }
    }

    componentDidMount() {

        api.get(ApiRouteList.analyticProjectActive)
            .then((response) => {

                if (response.data.error) {
                    throw response;
                }

                response.data.content.map((item) => (
                    item.filter = true
                ))

                this.setState({
                    gaugeChart: response.data.content,
                })

            })
            .catch((error) => {
                console.log('CATCH');
                console.log(error);
            });

        api.get(ApiRouteList.filterProjectActive)
            .then((response) => {

                if (response.data.error) {
                    throw response;
                }

                this.setState({
                    options: response.data.content,
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

    handleSubmit(event) {
        event.preventDefault()

        let id = event.target.id.value

        if (event.target.time.value.trim() !== '' && id.trim() !== '') {

            api.post(ApiRouteList.saveEstimateTime + '/' + event.target.id.value, {
                time: event.target.time.value,
            })
                .then((response) => {

                    if (response.data.error) {
                        throw response;
                    }

                    notify({
                        status: 'success',
                        msg: response.data.message
                    })

                    let charts = this.state.gaugeChart

                    //@TODO Alterar o chart.map() para o uso do Redux e Hooks para melhorar o modo que o estado é manipulado
                    charts.map(item => {
                        if (item.key == id) {
                            item.estimateTime = parseFloat(response.data.content.estimateTime)
                            item.value = parseFloat(response.data.content.value)
                            item.maxValue = parseFloat(response.data.content.maxValue)
                        }
                    })

                    this.setState({
                        gaugeChart: charts
                    })

                    document.getElementById("form-" + id).reset();

                })
                .catch((error) => {
                    this.setState({
                        error: HandleErrorNotify(error)
                    })
                })

        } else {
            notify({
                status: 'error',
                msg: 'Por favor preencha um valor válido. Exemplo: 10',
                time: 3000
            })
        }
    }

    render() {
        return (
            <MasterLayout
                loaderActive={this.state.loaderActive}
            >

                <Card class='card-primary card-outline'>
                    <CardTitle>
                        <i className='fa fa-filter'/>
                        Filtros
                    </CardTitle>
                    <CardBody>
                        <form onSubmit={(e) => e.preventDefault()}>
                            <div className="row">
                                <div className="col-md-12">
                                    <div className="form-group">
                                        <label className="w-100">
                                            Filtro de projetos desativados
                                            <Select
                                                value={this.state.selectedOption}
                                                onChange={(selectedOption) => {
                                                    return this.handleChange(selectedOption)
                                                }}
                                                options={this.state.options}
                                                isMulti={true}
                                                isClearable={false}
                                                components={animatedComponents}
                                                placeholder={'Selecione...'}
                                                closeMenuOnSelect={false}
                                            />
                                        </label>
                                    </div>
                                </div>
                                <div className="col-md-12">
                                    <div className="form-group">
                                        <label className="w-100">
                                            Busca pelo nome
                                            <input type="text"
                                                   className='form-control'
                                                   onChange={this.handleFileNameForm}/>
                                        </label>
                                    </div>
                                </div>
                                <div className="col-md-12">
                                    <div className="form-group">
                                        <label className="w-100">
                                            Ordenar
                                            <select
                                                className="form-control"
                                                onChange={this.changeOrder}>
                                                <option value="">Sem filtro</option>
                                                <option value={MAIOR_TEMPO_ESTIMADO}>Maior tempo estimado</option>
                                                <option value={MENOR_TEMPO_ESTIMADO}>Menor tempo estimado</option>
                                                <option value={MAIOR_TEMPO_GASTO}>Maior tempo gasto</option>
                                                <option value={MENOR_TEMPO_GASTO}>Menor tempo gasto</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </CardBody>
                </Card>

                <div className="grid-chart">
                    {
                        this.state.gaugeChart.map((item, index) => {
                            let showBlock = true;

                            if (!item.showBlock) {
                                showBlock = false;
                            }

                            if (showBlock && !item.filter) {
                                showBlock = false
                            }

                            if (showBlock) {
                                return (
                                    <div key={item.key} className="item-chart">
                                        <Card>

                                            <CardTitle>
                                                {item.title}
                                            </CardTitle>

                                            <CardBody>
                                                <GaugeChart
                                                    value={item.value}
                                                    minValue={item.minValue}
                                                    maxValue={item.maxValue}
                                                    segments={3}
                                                />
                                                <div className="block-estimate-time">

                                                    <div className="estimate-time">
                                                        Tempo estimado: <span>{item.estimateTime} hrs</span>
                                                    </div>

                                                    <form onSubmit={this.handleSubmit} id={"form-" + item.key}>
                                                        <div className="row">
                                                            <div className="col-10">
                                                                <input
                                                                    className="form-control"
                                                                    type="tel"
                                                                    name="time"
                                                                />
                                                            </div>
                                                            <input type="hidden" value={item.key} name="id"/>

                                                            <div className="col-2 text-center">
                                                                <button
                                                                    className="btn btn-success"
                                                                    type="submit"
                                                                ><i className="fa fa-check"/></button>
                                                            </div>

                                                        </div>

                                                    </form>

                                                </div>
                                            </CardBody>

                                        </Card>
                                    </div>
                                )
                            } else {
                                return null
                            }
                        })
                    }
                </div>

            </MasterLayout>
        )
    }
}

export default AnalyticActive