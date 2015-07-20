var Camera       = require('./Camera');
var PhotosClient = require('../../Api/PhotosClient');
var React        = require('react');
var Router       = require('react-router');

var Upload = React.createClass({
    mixins : [Router.Navigation],

    handleSubmit : function () {
        var quote       = this.refs.quote.getDOMNode().value;
        var boooth_file = this.refs.boooth_file.getDOMNode().files[0];
        var boooth_snap = this.refs.camera.getSnappedImage();

        var form_data = new FormData();
        form_data.append('quote', quote);

        if (boooth_snap !== null) {
            form_data.append('uploaded_file', boooth_snap);
        }
        else {
            form_data.append('uploaded_file', boooth_file);
        }

        var redirection = function () {
            this.transitionTo('app')
        };

        PhotosClient.uploadNew(form_data, redirection.bind(this));
    },

    render : function() {
        return (
            <div>
                <Camera ref="camera" />
                <input type="text" name="quote" ref="quote" placeholder="Sup!" /><br/>
                <input type="file" name="boooth_file" ref="boooth_file" /><br/>
                <button type="button" onClick={this.handleSubmit}>Submit</button>
            </div>
        );
    }
});

module.exports = Upload;