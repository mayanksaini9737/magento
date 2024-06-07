var j = jQuery.noConflict();
j(document).ready(function () {

    var eventCounter = 0;
    var conditions = ['from', 'subject'];
    var operators = ['=', 'like'];
    // var removeUrl = '';
    console.log(typeof(eventsData));

    if (typeof eventsData !== 'undefined' && eventsData.length > 0) {
        let currentGroupId = null;
        let currentEventIndex = -1;
        let ruleIndex = 1;
        for (var i = 0; i < eventsData.length; i++) {
            let eventData = eventsData[i];

            if (eventData.group_id !== currentGroupId) {
                currentGroupId = eventData.group_id;
                var eventDiv = createEventDiv(eventCounter, eventData);
                j(".container").append(eventDiv);
                currentEventIndex = eventCounter;
                eventCounter++;
            } else {
                ruleIndex++;
                var ruleRow = createRuleRow(currentEventIndex, ruleIndex, eventData);
                j(".container").find('.event[data-event-index=' + currentEventIndex + '] table tr').last().before(ruleRow);
                // j(".container").find('.event[data-event-index=' + currentEventIndex + '] table tr.add-rule').append(ruleRow);
                // console.log(j(".container").find('.event[data-event-index=' + currentEventIndex + '] table tr').last());
                updateRowspan(currentEventIndex);
            }
        }
    }

    j("body").on("click", ".add-event", function (e) {
        e.preventDefault();
        // removeUrl = j(this).data('url');
        var eventDiv = createEventDiv(eventCounter++);
        j(".container").append(eventDiv);
        j(".container").append(j(this)); 
    });

    j("body").on("click", ".add-rule", function (e) {
        e.preventDefault();
        var eventIndex = j(this).closest('.event').data('event-index');
        var ruleCounter = j(this).closest('.event').data('rule-counter');
        var ruleRow = createRuleRow(eventIndex, ruleCounter + 1);
        j(this).closest('.event').data('rule-counter', ruleCounter + 1);
        j(this).closest('.event').find('table').append(ruleRow);
        j(this).closest('.event').find('table').append(j(this)); 
        updateRowspan(eventIndex);
    });

    j("body").on("click", ".remove-rule", function (e) {
        e.preventDefault();
        var eventIndex = j(this).closest('.event').data('event-index');
        var eventId = j(this).closest('.rule-row').find('.event-id').val();
        if (j(this).closest('table').find('.rule-row').length > 1) {
            j(this).closest('.rule-row').remove();
            updateRowspan(eventIndex);
        }
        if (eventId === ''){
            return;
        }
        new Ajax.Request(removeUrl, {
            method: "post",
            parameters: { event: eventId },
            onSuccess: function (response) {
                console.log(response);
            },
        });
    });

    j("body").on("click", ".remove-event", function (e) {
        e.preventDefault();
        var groupId = j(this).closest('.event').find('.group-id').val();
        j(this).closest('.event').remove();
        // console.log(groupId);
        if (groupId === ''){
            return;
        }
        new Ajax.Request(removeUrl, {
            method: "post",
            parameters: { group: groupId },
            onSuccess: function (response) {
                console.log(response);
            },
        });
    });

    function createRuleRow(eventIndex, ruleIndex, eventData = {}) {
        var conditionOn = eventData.condition_on || '';
        var operator = eventData.operator || '';
        var value = eventData.value || '';
        var event_id = eventData.event_id || '';

        var ruleRow = `
            <tr class="rule-row">
                <td>
                    <input type="hidden" class="event-id" name="events[${eventIndex}][rules][${ruleIndex}][event_id]" value="${event_id}" >
                    <select class="subject-dropdown" name="events[${eventIndex}][rules][${ruleIndex}][condition_on]">
                        ${conditions.map(cond => `<option value="${cond}"${conditionOn === cond ? ' selected' : ''}>${cond.charAt(0).toUpperCase() + cond.slice(1)}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <select class="operator-dropdown" name="events[${eventIndex}][rules][${ruleIndex}][operator]">
                        ${operators.map(op => `<option value="${op}"${operator === op ? ' selected' : ''}>${op}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <input type="text" class="input-field" placeholder="Enter value" name="events[${eventIndex}][rules][${ruleIndex}][value]" value="${value}">
                </td>
                <td>
                    <button type="button" class="remove-rule">Remove Rule</button>
                </td>
            </tr>`;
        return ruleRow;
    }

    function createEventDiv(eventIndex, eventData = {}) {
        var event = eventData.event || '';
        var conditionOn = eventData.condition_on || '';
        var operator = eventData.operator || '';
        var value = eventData.value || '';
        var group_id = eventData.group_id || '';
        var event_id = eventData.event_id || '';

        var eventDiv = `
            <div class="event" data-event-index="${eventIndex}" data-rule-counter="0">
                <button type="button" class="remove-event" value="${group_id}">Remove Event</button>
                <table>
                    <input type="hidden" class="group-id" name="events[${eventIndex}][group_id]" value="${group_id}" >
                    <tr class="rule-row">
                        <td>
                            <input type="hidden" class="event-id" name="events[${eventIndex}][rules][0][event_id]" value="${event_id}" >
                            <select class="subject-dropdown" name="events[${eventIndex}][rules][0][condition_on]">
                                ${conditions.map(cond => `<option value="${cond}"${conditionOn === cond ? ' selected' : ''}>${cond.charAt(0).toUpperCase() + cond.slice(1)}</option>`).join('')}
                            </select>
                        </td>
                        <td>
                            <select class="operator-dropdown" name="events[${eventIndex}][rules][0][operator]">
                                ${operators.map(op => `<option value="${op}"${operator === op ? ' selected' : ''}>${op}</option>`).join('')}
                            </select>
                        </td>
                        <td>
                            <input type="text" class="input-field" placeholder="Enter value" name="events[${eventIndex}][rules][0][value]" value="${value}">
                        </td>
                        <td width="200px">
                        </td>
                        <td class="event-cell" rowspan="1">
                            <input type="text" class="event-input" placeholder="Enter event" name="events[${eventIndex}][event]" value="${event}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <button type="button" class="add-rule">Add Rule</button>
                        </td>
                    </tr>
                </table>
            </div>`;
        return eventDiv;
    }

    function updateRowspan(eventIndex) {
        var eventCell = j('.event[data-event-index=' + eventIndex + ']').find('.event-cell');
        var ruleRows = j('.event[data-event-index=' + eventIndex + ']').find('.rule-row').length;
        eventCell.attr('rowspan', ruleRows);
    }

    // j(".container").after('<button type="button" class="add-event">Add Event</button>');
});