export const ruPhoneFrom9 = ( str ) => {
	return '8(' + str.substr(0,3) + ')'+ str.substr(3,3)+'-'+str.substr(6,2)+'-'+str.substr(8,2)
}