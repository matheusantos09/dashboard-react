import React, {Component} from 'react'
import Routes from './services/routes';

/* Styles */
import './assets/scss/App.scss'
// import 'bootstrap/dist/css/bootstrap.min.css';

/* Import fonts and styles */
// import 'react-toastify/dist/ReactToastify.css'

class App extends Component {

    render() {
        return (
            <div>
                <Routes/>
            </div>
        );
    }

}

export default App;
