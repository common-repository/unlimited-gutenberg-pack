/* eslint-disable */
let gutenberg_pack_deactivated_blocks = gutenberg_pack_deactivate_blocks.deactivated_blocks;

// If we are recieving an object, let's convert it into an array.
if ( ! gutenberg_pack_deactivated_blocks.length ) {
    gutenberg_pack_deactivated_blocks =
        Object.keys( gutenberg_pack_deactivated_blocks ).map( key => gutenberg_pack_deactivated_blocks[ key ] )
}
 // Just in case let's check if function exists.
if ( typeof wp.blocks.unregisterBlockType !== "undefined" ) {
    gutenberg_pack_deactivated_blocks.forEach( block => wp.blocks.unregisterBlockType( block ) )
}
