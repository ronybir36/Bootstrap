@app.route("/", methods=["GET", "POST"])
def register():
    if request.method == "POST":
        username = request.form.get("username")
        email = request.form.get("email")
        password = request.form.get("password")
        gender = request.form.get("gender")
        message = request.form.get("message")
        purpose = request.form.get("purpose")
        newsletter = "Yes" if request.form.get("newsletter") else "No"

        # File upload
        file = request.files.get("file")
        filename = None
        if file and file.filename:
            filename = file.filename
            file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))

        # Simple server-side validation
        errors = []
        if not username: errors.append("Name is required")
        if not email: errors.append("Email is required")
        if not password: errors.append("Password is required")

        if errors:
            return "<h3>Errors:</h3>" + "<br>".join(errors)
        
        # Show success
        return f'''
        <h3>Form submitted successfully!</h3>
        <p>Name: {username}</p>
        <p>Email: {email}</p>
        <p>Gender: {gender}</p>
        <p>Message: {message}</p>
        <p>Purpose: {purpose}</p>
        <p>Newsletter: {newsletter}</p>
        <p>Uploaded File: {filename if filename else 'No file'}</p>
        '''
    return render_template_string(form_html)

if __name__ == "__main__":
    app.run(debug=True)