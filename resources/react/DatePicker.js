import React from "react";

export default class DatePicker extends React.Component
{
	constructor(props) {
		super();

		let date
		if (props.defaultValue) {
			let splitDV = props.defaultValue.split('.')
			if (splitDV[0].charAt(0) == "0") splitDV[0] = splitDV[0].substr(1)
			if (splitDV[1].charAt(0) == "0") splitDV[1] = splitDV[1].substr(1)
			let defaultValue = splitDV[2]+'-'+splitDV[1]+'-'+splitDV[0]
			date = new Date(defaultValue)
		} else {
			date = new Date()
		}
		date.setMonth(date.getMonth() + 1);

		this.state = {
			active: false,
			actualDate: {
				year: date.getFullYear(),
				month: date.getMonth() ? date.getMonth() : 12,
				day: date.getDate(),
			},
			year: date.getFullYear(),
			month: date.getMonth() ? date.getMonth() : 12,
			months: {
				1: 'Január',
				2: 'Február',
				3: 'Marec',
				4: 'Apríl',
				5: 'Máj',
				6: 'Jún',
				7: 'Júl',
				8: 'August',
				9: 'September',
				10: 'Október',
				11: 'November',
				12: 'December',
			},
			days: {
				1: 'Po',
				2: 'Ut',
				3: 'St',
				4: 'Št',
				5: 'Pi',
				6: 'So',
				7: 'Ne'
			},
			daysHtml: ''
		}

		this.state.daysHtml = this.renderDays()

		let day = date.getDate().toString()
		if (day.length == 1) {
			day = "0" + day
		}
		let month = date.getMonth().toString()
		if (month.length == 1) {
			month = "0" + month
		}

		let sDate = day + '.' + month + '.' + date.getFullYear()

		props.onChange(props.name, sDate)
	}


	getDay = (date) => {
		let day = date.getDay()
		if (day == 0) {
			day = 7
		}

		return day
	}


	handleDatePickerOpen = () => {
		this.setState({
			active: true
		})
	}


	handleDatePickerLeave = () => {
		if (this.state.active) {
			this.setState({
				active: false
			})
		}
	}


	renderDays = () => {
		let daysHeader = Object.keys(this.state.days).map(day => (
			<span className="day-name" key={day}>{this.state.days[ day ]}</span>
		))

		let firstDay = new Date(this.state.year, this.state.month-1, 1)
		let lastDay = new Date(this.state.year, this.state.month, 0).getDate()
		let offset = this.getDay(firstDay)
		let actualDay = 1

		let weeks = []
		let weekDays
		let activeDayInWeek

		for (let w = 0; w < 6; w++) {
			weekDays = [];
			activeDayInWeek = false

			for (let d = 1; d < 8; d++) {
				if (d < offset) {
					weekDays.push( '-' )
				} else if (actualDay > lastDay) {
					weekDays.push( '-' )
					actualDay++
					offset++
					if (offset == 8) {
						offset = 1
					}
				} else {
					activeDayInWeek = true
					weekDays.push(actualDay)
					actualDay++
					offset++
					if (offset == 8) {
						offset = 1
					}
				}
			}
			if (activeDayInWeek) {
				weeks.push(
					weekDays.map((day, index) => {
						let today = new Date()
						today.setMonth(today.getMonth() + 1);
						let spanClass = 'day'
						if (day == '-') {
							spanClass += ' empty-day'
						}
						if (this.state.year == today.getFullYear() && this.state.month == today.getMonth() && day == today.getDate()) {
							spanClass += ' today'
						}
						if (this.state.year == this.state.actualDate.year && this.state.month == this.state.actualDate.month && day == this.state.actualDate.day) {
							spanClass += ' selected-day'
						}

						return (
							<span className={spanClass} key={index} onClick={() => this.handleDateClick(day)}>{day}</span>
						)
					})
				)
			}
		}

		return (
			<div className="days">
				<div className="days-header">{daysHeader}</div>
				<div className="days-content">
					{weeks.map(function (week, index) { return (
						<div className="week" key={index}>{week}</div>
					)})}
				</div>
			</div>
		)
	}


	handleYearChangeClick = (action) => {
		let year = this.state.year
		if (action == '+') {
			year++
		} else {
			year--
		}

		this.setState({
			year: year
		}, () => {
			let daysHtml = this.renderDays()
			this.setState({
				daysHtml: daysHtml
			})
		})
	}


	handleMonthChangeClick = (action) => {
		let month = this.state.month
		let year = this.state.year
		if (action == '+') {
			month++
			if (month == 13) {
				month = 1
				year++
			}
		} else {
			month--
			if (month == 0) {
				month = 12
				year--
			}
		}

		this.setState({
			month: month,
			year: year
		}, () => {
			let daysHtml = this.renderDays()
			this.setState({
				daysHtml: daysHtml
			})
		})
	}


	handleDateClick = (day) => {
		if (day == '-') {
			return
		}

		let year = this.state.year
		let month = this.state.month

		this.setState({
			active: false,
			actualDate: {
				year: year,
				month: month,
				day: day
			}
		}, () => {
			let daysHtml = this.renderDays()
			this.setState({
				daysHtml: daysHtml
			})
		})

		day = day.toString()
		if (day.length == 1) {
			day = "0" + day
		}
		month = month.toString()
		if (month.length == 1) {
			month = "0" + month
		}

		let date = day + '.' + month + '.' + year

		this.props.onChange(this.props.name, date)
	}


	render() {
		return (
			<div className={'my-date-picker'}>
				<div className="date-picker-wrapper" onMouseLeave={this.handleDatePickerLeave}>
					<input type="hidden" name={this.props.name} value={this.props.defaultValue} />
					<input type="text" name={this.props.name+'TextValue'} value={this.state.actualDate.day + '.' + this.state.actualDate.month + '.' + this.state.actualDate.year} readOnly={true} onClick={this.handleDatePickerOpen} />
					<span className="calendar-icon" onClick={this.handleDatePickerOpen}><i className="fas fa-calendar-alt" /></span>
					<div className="calendar-option" hidden={!this.state.active} >
						<div className="option">
							<span className="option-button option-button-back" onClick={() => this.handleYearChangeClick('-')}><i className="fas fa-arrow-circle-left" /></span>
							<span className="year-option">{this.state.year}</span>
							<span className="option-button option-button-next" onClick={() => this.handleYearChangeClick('+')}><i className="fas fa-arrow-circle-right" /></span>
						</div>
						<div className="option">
							<span className="option-button option-button-back" onClick={() => this.handleMonthChangeClick('-')}><i className="fas fa-arrow-circle-left" /></span>
							<span className="month-option">{this.state.months[ this.state.month ]}</span>
							<span className="option-button option-button-next" onClick={() => this.handleMonthChangeClick('+')}><i className="fas fa-arrow-circle-right" /></span>
						</div>
						{this.state.daysHtml}
					</div>
				</div>
			</div>
		)
	}
}