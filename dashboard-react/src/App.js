import React, {Component} from 'react'
import Routes from './services/routes';

/* Styles */
import './assets/scss/App.scss'
import {ToastContainerCustom} from "./components/Notification/Notify";
// import 'bootstrap/dist/css/bootstrap.min.css';

/* Import fonts and styles */

import 'react-toastify/dist/ReactToastify.css'

class App extends Component {

    render() {
        return (
            <div>
                <Routes/>

                <ToastContainerCustom/>
            </div>
        );
    }

}

export default App;
