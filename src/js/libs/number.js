'use strict'

export function numberSplitted(num) {
	num = Number(num).toFixed(2);
	return String(num).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ')
}
