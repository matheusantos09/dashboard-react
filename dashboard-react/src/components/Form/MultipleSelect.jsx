import React, {Component} from 'react';
import Select from 'react-select';
import makeAnimated from 'react-select/animated';

const animatedComponents = makeAnimated()

class MultipleSelect extends Component {
    constructor(props) {
        super(props)

        this.state = {
            selectedOption: props.selectedOption,
            options: props.options
        }
    }

    handleChange = selectedOption => {
        this.setState({selectedOption});
        // console.log(`Option selected:`, selectedOption);
        return selectedOption
    };

    //@TODO Bug atual da lib o closeMenuOnSelect nao é desativado
    render() {

        return (
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
        );
    }
}

MultipleSelect.defaultProps = {
    selectedOption: [
        {value: 'default', label: 'Default'},
    ],
    options: [
        {value: 'default', label: 'Default'},
        {value: 'default2', label: 'Default2'},
    ]
}

export default MultipleSelect