import React, {Component} from 'react'

import Header from '../Header/Header'
import Footer from '../Footer/Footer'
import Sidebar from '../Sidebar/Sidebar';

export class MasterLayout extends Component {

    componentDidMount() {
        var body = document.getElementsByTagName('body')

        body[0].classList.add('sidebar-mini')
        body[0].classList.add('layout-fixed')
    }

    render() {
        return (
            <div className="wrapper">

                <Header/>

                <Sidebar/>

                <div className="content-wrapper">

                    <div className="content-header">
                    </div>

                    {this.props.children}
                </div>

                <Footer/>

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