# TODO List

- [X] Task 1: Move the like and dislike button so it lines up with the content of the comment
- [X] Task 2: Create an API to return the client 10 comments at a time to paginate comments
- [X] Task 3: Change the way of displaying comments in the blade and js file of postCard to reflect that pagination change
- [X] Task 4: Implement the like and dislike comment feature
- [X] Task 5: Change the comment index API to return blade views instead of comment objects
- [X] Task 6: Fix the index API to return according to post id, and move it to the show API instead
- [X] Task 7: Fix comment pagination
- [X] Task 8: Refactor and polish the entire codebase
- [X] Task 9: Add comment editing and deleting
- [X] Task 10: Add alerts when doing non-AJAX actions
- [X] Task 11: Add spinners and disabling buttons when submitting edits and other stuff
- [X] Task 12: Add a "change email" form in the verify email page
- [X] Task 13: Add a confirm delete pop up when deleting posts, accounts, and relapse data, and there deletions if they exist
- [ ] Task 14: Modify the quotes and fill it with quotes that actually makes sense
- [X] Task 15: Add a feature to plot the chart over a choice of period of time
- [X] Task 16: Create a table called "daily_logs"
- [X] Task 17: Create the migration file and its contents for daily_logs (user_id foreignID, date, mood (int), journal (text))
- [X] Task 18: Create a calendar UI in dashboard to display each day of relapse (for example, coloring a day red to show that that day the user relapsed)
- [X] Task 19: Create a daily log creation feature where users can easily input their mood and journal entry.
- [X] Task 20: Create a feature where if the user clicks on a day on the calendar, they can view the mood in the form of a little emoji and their journal
- [X] Task 21: Create a UI to prompt the user for their mood and journal for the day in the dashboard
- [X] Task 22: Create a controller to handle the user inputted mood and journal
- [X] Task 23: Create individual profiles for each users to view
- [X] Task 24: Create a way to make profiles private and public  
        - ~Create the migrations to add a public column to users~  
        - ~Create a notifications dropdown to display latest follow requests~  
        - ~Create the button to accept follow requests~  
        - ~Create the button to deny follow requests~  
        - ~Create the button to follow back person~  
        - ~Create the UI for unfollowed private accounts similar to instagram~  
        - ~Modify the controllers to restrict unfollowed accounts from viewing private accounts~  
        - ~Create the database to store follow request notifications~  
- [ ] Task 25: Integrate google accounts as another way to register and logging in
- [X] Task 26: Add a following and follower system
- [X] Task 27: Add a page to display latest following's posts
- [X] Task 28: Add account info onto profiles: Account join date, etc
- [X] Task 29: Index certain tables:  
        user_id for relapse_tracks  
        created_at for posts  
        created_at for comments    
        user_id post_id comment_id for post_likes&dislikes and comment_likes&dislikes and a composite index for user_id and post&comment_id  
        composite index user_id date for daily_logs
- [X] Task 30: Move account creation date to a separate account info tab
- [X] Task 31: Make a popup to check who the user is following or who is following user by clicking on the follower and following text on profile:<br>
        - ~~Create the event listener to listen for click~~  
        - ~~Create the popup with swal2~~  
        - ~~Create the API to return the client a list of users the user is following (doesn't have to be the entire user object, just need pfp and username)~~  
        - ~~Create the AJAX request logic to display the list on the popup~~  
        - ~~Create a follow button for each account in the list like in instagram~~
        - ~~Make the follow button actually work~~  
- [ ] Task 32: Add spinners to all AJAX requested resources
- [ ] Task 33: Use swal2 to implement error messages on the front end