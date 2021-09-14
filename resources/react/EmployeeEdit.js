import React from "react"
import Modal from "react-modal"
import DatePicker from "./DatePicker";

export default class EmployeeEdit extends React.Component
{
	state = {
		formData: this.props.employee
	}

	valueChange = (e) => {
		let formData = this.state.formData
		formData[ e.target.name ] = e.target.value
		this.setState({
			formData: formData
		})
	}

	dateValueChange = (name, value) => {
		let formData = this.state.formData
		formData[name] = value
		this.setState({
			formData: formData
		})
	}

	render() {
		return (
			<Modal isOpen={this.props.open} className="modal-wrapper" overlayClassName="modal-overlay">
				<div className="employee-edit-wrapper">
					<div className="modal-title">{this.props.title}</div>

					<table className="employee-edit-form">
						<tbody>
							<tr className="form-row">
								<td className="label">Krstné meno:</td>
								<td><input type="text" name="firstName" value={this.state.formData.firstName} onChange={this.valueChange} /></td>
							</tr>
							<tr className="form-row">
								<td className="label">Priezvisko:</td>
								<td><input type="text" name="lastName" value={this.state.formData.lastName} onChange={this.valueChange} /></td>
							</tr>
							<tr className="form-row">
								<td className="label">Telefón:</td>
								<td><input type="text" name="phone" value={this.state.formData.phone} onChange={this.valueChange} /></td>
							</tr>
							<tr className="form-row">
								<td className="label">E-mail:</td>
								<td><input type="text" name="email" value={this.state.formData.email} onChange={this.valueChange} /></td>
							</tr>
							<tr className="form-row">
								<td className="label">Dátum narodenia:</td>
								<td><DatePicker name="birthDate" defaultValue={this.state.formData.birthDate} onChange={this.dateValueChange} /></td>
							</tr>
							<tr className="form-row">
								<td className="label">Dátum nástupu:</td>
								<td><DatePicker name="employmentDate" defaultValue={this.state.formData.employmentDate} onChange={this.dateValueChange} /></td>
							</tr>
						</tbody>
					</table>

					<div className="modal-button-wrapper">
						<div className="save-employee button" onClick={() => this.props.save(this.state.formData)}>
							<i className="icon fas fa-save" />
							<span className="text">Uložiť</span>
						</div>
						<div className="close-modal button" onClick={this.props.close}>
							<i className="icon fas fa-times" />
							<span className="text">Zavrieť</span>
						</div>
					</div>
				</div>
			</Modal>
		)
	}
}