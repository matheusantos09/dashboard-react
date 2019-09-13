import React, {Component} from 'react'

import Header from '../Header/Header'
import Footer from '../Footer/Footer'
import Sidebar from '../Sidebar/Sidebar';
import Loader from '../Loader/Loader';

export class MasterLayout extends Component {

    componentDidMount() {
        var body = document.getElementsByTagName('body')

        body[0].classList.add('sidebar-mini')
        body[0].classList.add('layout-fixed')

    }

    render() {
        return (
            <div className="wrapper">
                <Loader
                    active={this.props.loaderActive}
                >

                    <Header/>

                    <Sidebar/>

                    <div className="content-wrapper">

                        <div className="content-header">
                        </div>
                        <section className="content">
                            <div className="container-fluid">

                                {this.props.children}
                            </div>
                        </section>
                    </div>

                    <Footer/>

                </Loader>
            </div>
        )
    }
}

export class MasterLogin extends Component {

    componentDidMount() {
        var body = document.getElementsByTagName('body')

        body[0].classList.add('login-page')
    }

    render() {
        return (
            <div className="login-box">

                {this.props.children}

            </div>
        )
    }
}

export default MasterLayout

MasterLayout.defaultProps = {
    loaderActive: true
}