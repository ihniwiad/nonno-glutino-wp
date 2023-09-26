import DomData from './dom-data'
import Selectors from './../utilities/selectors'


const FUNCTION_ATTR = Selectors.functionAttr

const functionElems = document.querySelectorAll( '[' + FUNCTION_ATTR + ']' )

// add to DomData
if ( typeof functionElems !== 'undefined' && functionElems.length > 0 ) {
  // stay compatile with older Safari
  Array.from( functionElems ).forEach( elem => {
    const key = elem.getAttribute( FUNCTION_ATTR )
    DomData.addElem( elem, key )
  } )
}

export default functionElems

