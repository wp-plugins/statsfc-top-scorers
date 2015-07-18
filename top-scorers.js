function StatsFC_TopScorers(key) {
	this.domain			= 'https://api.statsfc.com';
	this.referer		= '';
	this.key			= key;
	this.competition	= '';
	this.team			= '';
	this.date			= '';
	this.limit			= '';
	this.highlight		= '';
	this.showBadges		= false;

	var $j = jQuery;

	this.display = function(placeholder) {
		if (placeholder.length == 0) {
			return;
		}

		var $placeholder = $j('#' + placeholder);

		if ($placeholder.length == 0) {
			return;
		}

		if (this.referer.length == 0) {
			this.referer = window.location.hostname;
		}

		var $container = $j('<div>').addClass('sfc_topscorers');

		// Store globals variables here so we can use it later.
		var domain		= this.domain;
		var team		= this.team;
		var highlight	= this.highlight;
		var showBadges	= (this.showBadges === true || this.showBadges === 'true');

		$j.getJSON(
			domain + '/crowdscores/top-scorers.php?callback=?',
			{
				key:			this.key,
				domain:			this.referer,
				competition:	this.competition,
				team:			this.team,
				date:			this.date,
				limit:			this.limit,
				badges:			showBadges
			},
			function(data) {
				if (data.error) {
					$container.append(
						$j('<p>').css('text-align', 'center').append(
							$j('<a>').attr({ href: 'https://statsfc.com', title: 'Football widgets and API', target: '_blank' }).text('StatsFC.com'),
							' – ',
							data.error
						)
					);

					return;
				}

				var $table = $j('<table>');
				var $thead = $j('<thead>');
				var $tbody = $j('<tbody>');

				var $team = null;

				if (team.length == 0) {
					$team = $j('<th>').text('Team');

					if (showBadges) {
						$team.addClass('sfc_team');
					}
				}

				$thead.append(
					$j('<tr>').append(
						$j('<th>').text('Player'),
						$team,
						$j('<th>').addClass('sfc_numeric').text('Goals')
					)
				);

				if (data.scorers.length > 0) {
					$j.each(data.scorers, function(key, val) {
						var $row = $j('<tr>');
						if (highlight == val.team) {
							$row.addClass('sfc_highlight');
						}

						var $team = null;

						if (team.length == 0) {
							var $team = $j('<td>').addClass('sfc_badge_' + val.path).text(val.team);

							if (showBadges) {
								$team.addClass('sfc_team').css('background-image', 'url(https://api.statsfc.com/kit/' + val.path + '.svg)');
							}
						}

						$row.append(
							$j('<td>').addClass('sfc_player').text(val.player),
							$team,
							$j('<td>').addClass('sfc_goals sfc_numeric').text(val.goals)
						);

						$tbody.append($row);
					});

					$table.append($thead, $tbody);
				}

				$container.append($table);

				if (data.customer.attribution) {
					$container.append(
						$j('<div>').attr('class', 'sfc_footer').append(
							$j('<p>').append(
								$j('<small>').append('Powered by ').append(
									$j('<a>').attr({ href: 'https://statsfc.com', title: 'StatsFC – Football widgets', target: '_blank' }).text('StatsFC.com')
								).append('. Fan data via ').append(
									$j('<a>').attr({ href: 'https://crowdscores.com', title: 'CrowdScores', target: '_blank' }).text('CrowdScores.com')
								)
							)
						)
					);
				}
			}
		);

		$j('#' + placeholder).append($container);
	};
}
