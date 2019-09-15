import React, {Component} from 'react'

export class CardTitle extends Component {

    render() {
        return (
            <div className="card-header d-flex">
                <h3 className="card-title">
                    {this.props.children}
                </h3>
            </div>
        )
    }
}

export class CardBody extends Component {

    render() {
        return (
            <div className="card-body">
                <div className="tab-content p-0">
                    {this.props.children}
                </div>
            </div>
        )
    }
}

export class Card extends Component {

    render() {
        return (

            <div className={"card " + this.props.class}>
                {this.props.children}
            </div>

        )
    }
}

Card.defaultProps = {
    class: ''
}