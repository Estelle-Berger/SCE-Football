const selectElements = document.querySelectorAll(".form-check-input");

selectElements.forEach((selectElement) => {
    selectElement.addEventListener("change", (e) =>{
        const result = document.querySelector(".result");
        const selected = [];
            selectElements.forEach((element) => {
                if (element.checked){
                    selected.push( `- ${element.value}`);
                }
            })
        result.innerHTML = selected.join('<br>');
    });
});


document.addEventListener("DOMContentLoaded", function() {
    // Écoutez les changements de sélection dans le dropdown
    document.getElementById('team').addEventListener('change', function() {
        var selectedTeamId = this.value; // Obtenez l'ID de l'équipe sélectionnée
        var matchCards = document.querySelectorAll('.match-card'); // Sélectionnez toutes les cartes de match

        // Parcourez chaque carte de match
        matchCards.forEach(function(card) {
            var teamId = card.getAttribute('data-team-id'); // Obtenez l'ID de l'équipe de cette carte

            // Si l'ID de l'équipe de la carte correspond à l'équipe sélectionnée ou si aucune équipe n'est sélectionnée (valeur 0)
            if (selectedTeamId === '0' || teamId === selectedTeamId) {
                card.style.display = 'block'; // Affichez la carte
            } else {
                card.style.display = 'none'; // Masquez la carte
            }
        });
    })
});
const profil = document.getElementById('profil');
const save_team = document.getElementById('save-team');
save_team.addEventListener('click', ()=>{
    if (profil == 1){
        window.location.href= "./admin_users.php";
    }else{
        window.location.href="./admin.player.php"
    }
})


