$(document).ready(function(){$.ajax({url:"/transactions/",type:"GET",success:function(t){var s=t.map(function(t){return'<div "id"='+t.id+' class="test-Transactions__element" ><div class="test-Transactions__attribute">'+t.id+'</div><div class="test-Transactions__attribute">'+t.user_id+'</div><div class="test-Transactions__attribute">'+t.product.id+'</div><div class="test-Transactions__description">'+t.product.description+'</div><div class="test-Transactions__attribute">'+t.amount+'</div><div class="test-Transactions__attribute">'+t.currency+"</div></div>"});$(".test-Transactions__container").append(s)},error:function(t){console.log(t)}}),$(".test-Transactions__container").on("click",".test-Transactions__element",function(){$.ajax({url:"/transactions/"+this.attributes[0].value,type:"DELETE",success:function(t){},error:function(t){console.log(t)}}),$(this).slideUp()})});