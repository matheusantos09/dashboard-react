import React, {Component} from 'react'

class SmallBox extends Component {

    render() {
        return (
            <div className={this.props.columnGrid}>
                <div className={"small-box " + this.props.colorBox}>
                    <div className="inner">
                        <h3>{this.props.value}</h3>

                        <p>{this.props.title}</p>
                    </div>
                    <div className="icon">
                        <i className={"ion " + this.props.iconBox}/>
                    </div>
                </div>
            </div>
        )
    }
}

SmallBox.defaultProps = {
    value: 0,
    title: 'Default',
    colorBox: 'bg-info',
    iconBox: 'ion-bag',
    columnGrid: 'col-sm-12 col-md-6 col-lg-3 col-6'
}

export default SmallBox