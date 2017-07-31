'use strict'

export function numberSplitted(num) {
	num = Number(num).toFixed(2);
	return String(num).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ')
}

export function numberSplitted2(num) {
	num = Number(num).toFixed(2);
	num = String(num).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');

	num = num.replace('.', ',');
	return num;
}
