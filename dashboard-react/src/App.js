import React, {Component} from 'react'
import Routes from './services/routes';
import './assets/scss/App.scss'

/* Import fonts and styles */
import 'react-toastify/dist/ReactToastify.css'

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
