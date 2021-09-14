import React from "react"
import Modal from "react-modal"

export default class EmployeeRemove extends React.Component
{
	render() {
		return (
			<Modal isOpen={this.props.open} className="modal-wrapper" overlayClassName="modal-overlay">
				<div className="employee-remove-wrapper">
					<div className="modal-title">Odstránenie zamestnanca</div>

					<div>Naozaj si prajete zamestnanca natrvalo odstrániť z evidencie?</div>

					<div className="modal-button-wrapper">
						<div className="remove-employee button" onClick={this.props.remove}>
							<i className="icon fas fa-trash-alt" />
							<span className="text">Odstrániť</span>
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