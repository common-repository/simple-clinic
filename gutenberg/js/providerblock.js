/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('simpleclinic/providers', {
  title: 'Providers',
  icon: 'groups',
  category: 'widgets',
  attributes: {
    providers_per_row: {type: 'number', default: 4},
    align_center: {type: 'boolean', default: false},
    include_suffix: {type: 'boolean', default: false}
  },

/* This configures how the content and color fields will work, and sets up the necessary elements */

  edit: function(props) {
    function updateProvidersPerRow(value) {
      value = parseInt(value);
      props.setAttributes({providers_per_row: value})
    }
    function updateAlignCenter(value) {
      props.setAttributes({align_center: value})
    }
    function updateIncludeSuffix(value) {
      props.setAttributes({include_suffix: value})
    }

    return React.createElement(
      "div",
      null,
      React.createElement(wp.components.TextControl, {
        label: 'Providers shown per row (1-6)',
        type:'number',
        onChange: updateProvidersPerRow,
        value: parseInt(props.attributes.providers_per_row),
        min:1,
        max:6,
        step:1 }),
      React.createElement(wp.components.ToggleControl, {
        label: 'Center text',
        checked:props.attributes.align_center,
          onChange: updateAlignCenter
      }),
      React.createElement(wp.components.ToggleControl, {
        label: 'Include Suffixes',
        checked:props.attributes.include_suffix,
          onChange: updateIncludeSuffix
      })
    );
  },
  save: () => { return null }
})


wp.blocks.registerBlockType('simpleclinic/specialties', {
  title: 'Specialties',
  icon: 'lightbulb',
  category: 'widgets',
  attributes: {
    specialties_per_row: {type: 'number', default: 4},
    align_center: {type: 'boolean', default: false},
    hide_empty: {type: 'boolean', default: true},
    show_image: {type: 'boolean', default: true}
  },

/* This configures how the content and color fields will work, and sets up the necessary elements */

  edit: function(props) {
    function updateSpecialtiesPerRow(value) {
      value = parseInt(value);
      props.setAttributes({specialties_per_row: value})
    }
    function updateAlignCenter(value) {
      props.setAttributes({align_center: value})
    }
    function updateHideEmpty(value) {
      props.setAttributes({hide_empty: value})
    }

    function updateShowImages(value) {
      props.setAttributes({show_image: value})
    }

    return React.createElement(
      "div",
      null,
      React.createElement(wp.components.TextControl, {
        label: 'Specialties shown per row (1-6)',
        type:'number',
        onChange: updateSpecialtiesPerRow,
        value:parseInt(props.attributes.specialties_per_row),
        min:1,
        max:6,
        step:1 }),
      React.createElement(wp.components.ToggleControl, {
        label: 'Center text',
        checked:props.attributes.align_center,
          onChange: updateAlignCenter
      }),
      React.createElement(wp.components.ToggleControl, {
        label: 'Hide specialties with no providers',
        checked:props.attributes.hide_empty,
          onChange: updateHideEmpty
      }),
      React.createElement(wp.components.ToggleControl, {
        label: 'Show category image',
        checked:props.attributes.show_image,
          onChange: updateShowImages
      })
    );
  },
  save: () => { return null }
})
