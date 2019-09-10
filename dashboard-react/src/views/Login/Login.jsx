import React, {Component} from 'react'
import {Link, withRouter} from 'react-router-dom'
import api from '../../services/api'
import {login} from '../../services/auth'
import { ApiRouteList } from '../../services/routes';
import { MasterLogin } from "../../components/layouts/Master";

class Login extends Component {

    constructor() {
        super();

        this.handleSignIn = this.handleSignIn.bind(this);

    }

    state = {
        email: '',
        password: '',
        error: ''
    }

    handleSignIn = async e => {
        e.preventDefault()

        const {email, password} = this.state

        if (!email || !password) {
            this.setState({
                error: 'Por favor preencha a senha e e-mail corretamente'
            });
        } else {

            //@TODO melhorar validações para o login

            await api.post(ApiRouteList.login, {
                email, password
            })
                .then((response) => {

                    if (response.data.error) {
                        throw response;
                    }

                    login(response.data.token, response.data.expires_in)

                    this.props.history.push({
                        pathname: '/app',
                        state: {msgWelcome: 'Login feito com sucesso'}
                    })

                })
                .catch((error) => {
                    console.log('CATCH');
                    console.log(error);

                    if (typeof error.data !== 'undefined') {

                        console.log('tete');

                        var msgError = error.data.content ? error.data.content : error.content

                        console.log(error.data.content);

                        this.setState({
                            error: `${msgError}`
                        })

                    } else {

                        this.setState({
                            error: 'Estamos com problema para autenticar você tente novamente mais tarde'
                        })

                    }

                });

        }

    }

    render() {
        return (
            <MasterLogin>
                <div className="card">
                    <div className="card-body login-card-body">
                        <p className="login-box-msg">Sign in to start your session</p>

                        <form onSubmit={this.handleSignIn}>
                            <div className="input-group mb-3">
                                <input type="email" className="form-control" placeholder="Email"
                                       onChange={e => this.setState({email: e.target.value})}
                                />
                                <div className="input-group-append">
                                    <div className="input-group-text">
                                        <span className="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div className="input-group mb-3">
                                <input type="password" className="form-control" placeholder="Password"
                                       onChange={e => this.setState({password: e.target.value})}
                                />
                                <div className="input-group-append">
                                    <div className="input-group-text">
                                        <span className="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-8">
                                    <div className="icheck-primary">
                                        <input type="checkbox" id="remember" />
                                        <label htmlFor="remember">
                                            Lembre de mim
                                        </label>
                                    </div>
                                </div>
                                <div className="col-4">
                                    <button type="submit" className="btn btn-primary btn-block btn-flat">Sign In</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </MasterLogin>
        );
    }
}

export default withRouter(Login)