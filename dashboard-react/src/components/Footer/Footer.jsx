import React, {Component} from "react";

class Footer extends Component {

    //@TODO Criar salve em cache para exibir a versão e nome do sistema

    render() {
        return (
            <footer className="main-footer">
                <strong>Copyright &copy; 2014-2019</strong>
                All rights reserved.
                <div className="float-right d-none d-sm-inline-block">
                    <b>Version</b> 3.0.0-rc.1
                </div>
            </footer>
        )
    }

}

export default Footer